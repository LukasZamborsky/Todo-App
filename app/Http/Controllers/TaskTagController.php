<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;

class TaskTagController extends Controller
{
    
    public function attachTag(Request $request, Task $task)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id',
        ]);

        $task->tags()->attach($request->tag_id);

        return response()->json(['message' => 'Tag bol pridaný k úlohe.']);
    }

    
    public function detachTag(Request $request, Task $task)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id',
        ]);

        $task->tags()->detach($request->tag_id);

        return response()->json(['message' => 'Tag bol odstránený z úlohy.']);
    }

    
    public function tasksByTag(Tag $tag)
    {
        $tasks = $tag->tasks;

        return response()->json($tasks);
    }
}
