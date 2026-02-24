<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_assigned_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assigned_to' => $user->id]);

        $this->assertInstanceOf(User::class, $task->assignedUser);
        $this->assertEquals($user->id, $task->assignedUser->id);
    }

    public function test_task_belongs_to_creator()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $task->creator);
        $this->assertEquals($user->id, $task->creator->id);
    }

    public function test_scope_status()
    {
        Task::factory()->create(['status' => 'todo']);
        Task::factory()->create(['status' => 'done']);

        $tasks = Task::status('todo')->get();
        $this->assertCount(1, $tasks);
        $this->assertEquals('todo', $tasks->first()->status);
    }

    public function test_scope_for_user_respects_role()
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $user = User::factory()->create(['role' => UserRole::USER->value]);

        Task::factory()->count(2)->create(['assigned_to' => $user->id]);

        $this->actingAs($admin);
        $this->assertCount(2, Task::forUser($admin)->get());

        $this->actingAs($user);
        $this->assertCount(2, Task::forUser($user)->get()); // assigned to self
    }
}