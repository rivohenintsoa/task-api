<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Task $task): bool
    {
        return $user->role === UserRole::ADMIN || $task->assigned_to === $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->role === UserRole::ADMIN || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->role === UserRole::ADMIN || $task->assigned_to === $user->id;
    }
}
