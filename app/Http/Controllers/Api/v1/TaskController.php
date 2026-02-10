<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1BaseController;
use App\Http\Requests\Api\v1\Task\StoreTaskRequest;
use App\Http\Requests\Api\v1\Task\UpdateTaskRequest;
use App\Http\Resources\v1\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends V1BaseController
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->getPaginatedTasks($request->user(), $request->all());

        return $this->apiResponse(TaskResource::collection($tasks));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask(
            $request->validated(),
            $request->user()
        );

        return $this->apiResponse(new TaskResource($task), 'Task created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        Gate::authorize('view', $task);

        $task->load([
            'creator:id,name,email',
            'assignee:id,name,email',
            'team:id,name',
            'comments' => function ($query) {
                $query->with('user:id,name,email')
                      ->latest();
            }
        ]);

        return $this->apiResponse(new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task = $this->taskService->updateTask($task, $request->validated());

        return $this->apiResponse(new TaskResource($task), 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return $this->apiResponse(null, 'Task deleted successfully');
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('update', $task);

        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task = $this->taskService->updateStatus($task, $request->status);

        return $this->apiResponse(new TaskResource($task), 'Task status updated successfully');
    }

    /**
     * Assign a task to a user.
     */
    public function assignTask(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('update', $task);

        $request->validate([
            'assignee_id' => 'required|exists:users,id',
        ]);

        $task = $this->taskService->assignTask($task, $request->assignee_id);

        return $this->apiResponse(new TaskResource($task), 'Task assigned successfully');
    }
}
