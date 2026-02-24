<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getTasks(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Task::with(['assignedUser', 'creator'])
            ->status($filters['status'] ?? null)
            ->priority($filters['priority'] ?? null)
            ->assignedTo($filters['assigned_to'] ?? null)
            ->search($filters['search'] ?? null)
            ->forUser(auth()->user())
            ->paginate($perPage);
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}
