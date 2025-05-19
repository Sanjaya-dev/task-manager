<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        $task = Auth::user()->tasks()->orderBy('created_at', 'desc')->get();
        return inertia::render('Tasks/Index',[
            'tasks' => $task
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Auth::user()->tasks()->create(
            $request->only('title','description')
        );

        return redirect()->route('tasks.index');
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $task->update($request->only('title', 'description', 'completed'));

        return redirect()->route('tasks.index');
        
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        
        return redirect()->route('tasks.index');
    }
}
