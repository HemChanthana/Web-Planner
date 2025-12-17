<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // GET /api/tasks
    public function index(Request $request)
    {
        if($request->user()->role == 'admin'){
             $tasks = Task::with('user')->latest()->get(); 
        }  else {
        $tasks = Task::with('user')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();
    }
        $tasks = Task::with('user')->get(); 

        return TaskResource::collection($tasks);
    }

    // POST /api/tasks
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Normal,High',
            'end_date' => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->create($data);

        return new TaskResource($task->load('user'));
    }

    // GET /api/tasks/{task}
    public function show(Task $task, Request $request)
    {
        $this->authorizeTask($task, $request);

        return new TaskResource($task->load('user'));
    }

    // PUT /api/tasks/{task}
    public function update(Task $task, Request $request)
    {
        $this->authorizeTask($task, $request);

        $task->update(
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'required|in:Low,Normal,High',
                'status' => 'required|in:pending,in-progress,done',
                'end_date' => 'nullable|date',
            ])
        );

        return new TaskResource($task->load('user'));
    }

    // DELETE /api/tasks/{task}
    public function destroy(Task $task, Request $request)
    {
        $this->authorizeTask($task, $request);

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }


    private function authorizeTask(Task $task, Request $request)
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }
    }
}
