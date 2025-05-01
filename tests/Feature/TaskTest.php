<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Task description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task']);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_creation_requires_title()
    {
        $response = $this->postJson('/api/tasks', [
            'description' => 'Description without title'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

}
