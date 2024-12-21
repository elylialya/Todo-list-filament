<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo; // Pastikan Anda mengimpor model Todo

class TodoController extends Controller
{
    public function updateStatus(Request $request)
    {
        $todo = Todo::findOrFail($request->todoId);
        $todo->status = $request->status; // 'todo', 'in-progress', or 'done'
        $todo->save();
    
        return response()->json(['success' => true]);
    }
    
    public function updateTask(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'id' => 'required|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:todo,in-progress,done',
            'label' => 'required|string|max:255',
        ]);

        // Find the task by ID and update it
        $task = Task::find($validated['id']);
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->status = $validated['status'];
        $task->label = $validated['label'];
        $task->save(); // Save the updated task

        return response()->json(['success' => true, 'task' => $task]);
    }
    
}
