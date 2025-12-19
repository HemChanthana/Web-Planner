<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;




/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     type="object",
 *     required={"id","title","priority","status"},
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Finish assignment"),
 *     @OA\Property(property="description", type="string", example="Complete Swagger documentation"),
 *     @OA\Property(property="priority", type="string", enum={"Low","Normal","High"}, example="High"),
 *     @OA\Property(property="status", type="string", enum={"pending","in-progress","done"}, example="pending"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2025-01-10"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get list of tasks",
     *     tags={"Tasks"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            $tasks = Task::with('user')->latest()->get();
        } else {
            $tasks = Task::with('user')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get();
        }

        return TaskResource::collection($tasks);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","priority"},
     *             @OA\Property(property="title", type="string", example="Finish assignment"),
     *             @OA\Property(property="description", type="string", example="Complete Swagger documentation"),
     *             @OA\Property(property="priority", type="string", enum={"Low","Normal","High"}, example="High"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-01-10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a single task",
     *     tags={"Tasks"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function show(Task $task, Request $request)
    {
        $this->authorizeTask($task, $request);
        return new TaskResource($task->load('user'));
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","priority","status"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="priority", type="string", enum={"Low","Normal","High"}),
     *             @OA\Property(property="status", type="string", enum={"pending","in-progress","done"}),
     *             @OA\Property(property="end_date", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted"
     *     )
     * )
     */
    public function destroy(Task $task, Request $request)
    {
        $this->authorizeTask($task, $request);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    private function authorizeTask(Task $task, Request $request)
    {
        if ($request->user()->role !== 'admin' && $task->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }
    }
}
