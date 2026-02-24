<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private TaskService $taskService
    ) {}

    public function index()
    {
        $tasks = $this->taskService->getTasks(request()->only(['status', 'priority', 'search', 'assigned_to']));
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $data = array_merge($request->validated(), ['created_by' => auth()->id()]);
        $task = $this->taskService->createTask($data);
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->updateTask($task, $request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->taskService->deleteTask($task);
        return response()->json(['message' => 'Task supprimée avec succès.']);
    }
}
