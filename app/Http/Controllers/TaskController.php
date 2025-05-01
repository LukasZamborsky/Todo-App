<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Tag;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Task::with('tags');

        
        if ($search = $request->search) {
            $query->where('title', 'like', "%$search%");
        }

        
        if ($status = $request->status) {
            $query->where('is_completed', $status === 'completed');
        }

       
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        
        if ($sort = $request->sort) {
            if ($sort === 'title_asc') {
                $query->orderBy('title');
            } elseif ($sort === 'completed_first') {
                $query->orderByDesc('is_completed');
            } elseif ($sort === 'incomplete_first') {
                $query->orderBy('is_completed');
            }
        }

        return $query->paginate(10);
        
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'is_completed' => 'required|boolean',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'is_completed' => $data['is_completed'],
        ]);

        if (!empty($data['tag_ids'])) {
            $task->tags()->sync($data['tag_ids']);
        }

        return response()->json($task->load('tags'), 201);
    }



    public function show(Task $task)
    {
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'is_completed' => 'sometimes|boolean',
            'tag_ids' => 'sometimes|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $task->update($data);

        if (isset($data['tag_ids'])) {
            $task->tags()->sync($data['tag_ids']);
        }

        return response()->json($task);
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
    public function attachTags(Request $request, Task $task)
    {
        $request->validate([
            'tags'   => 'required|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

       
        $task->tags()->syncWithoutDetaching($request->input('tags'));

        return response()->json($task->load('tags'));
    }
    
    public function detachTag(Task $task, Tag $tag)
    {
        $task->tags()->detach($tag->id);

        return response()->json(null, 204);
    }
}
