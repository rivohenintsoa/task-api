<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_tasks_assigned()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assigned_to' => $user->id]);


        $this->assertCount(1, $user->tasksAssigned);
    }

    public function test_user_has_tasks_created()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by' => $user->id]);

        $this->assertCount(1, $user->tasksCreated);
    }
}
