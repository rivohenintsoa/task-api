<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function actingUser($role = 'user'): User
    {
        $user = User::factory()->create(['role' => $role]);
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_index_tasks_with_filters()
    {
        $user = $this->actingUser();
        $other = User::factory()->create();

        Task::factory()->create(['assigned_to' => $user->id, 'status' => 'todo', 'title' => 'Task A']);
        Task::factory()->create(['assigned_to' => $user->id, 'status' => 'done', 'title' => 'Task B']);
        Task::factory()->create(['assigned_to' => $other->id, 'status' => 'todo', 'title' => 'Task C']);

        // Sans filtre
        $response = $this->getJson('/api/v1/tasks');
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(2, 'data'); // uniquement les tâches assignées à $user

        // Filtre par status
        $response = $this->getJson('/api/v1/tasks?status=done');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Task B');

        // Filtre par assigned_to
        $response = $this->getJson("/api/v1/tasks?assigned_to={$other->id}");
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');

        // Filtre par recherche
        $response = $this->getJson('/api/v1/tasks?search=Task A');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Task A');
    }

    public function test_create_task()
    {
        $user = $this->actingUser();
        $assigned = User::factory()->create();

        $payload = [
            'title' => 'Nouvelle Tâche',
            'description' => 'Description',
            'status' => 'todo',
            'priority' => 'high',
            'assigned_to' => $assigned->id,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/v1/tasks', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Nouvelle Tâche')
            ->assertJsonPath('data.priority', 'Élevée');
    }

    public function test_show_own_task_and_forbidden_other()
    {
        $user = $this->actingUser();
        $task = Task::factory()->create(['assigned_to' => $user->id]);

        // Affiche sa propre tâche
        $response = $this->getJson("/api/v1/tasks/{$task->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $task->id);

        // Essaye d'afficher une tâche d'un autre utilisateur
        $otherUser = User::factory()->create(['role' => 'user']); // bien un utilisateur normal
        $otherTask = Task::factory()->create(['assigned_to' => $otherUser->id]);

        $response = $this->getJson("/api/v1/tasks/{$otherTask->id}");
        $response->assertStatus(403);
    }

    public function test_update_own_task_and_forbidden_other()
    {
        $user = $this->actingUser();
        $task = Task::factory()->create(['assigned_to' => $user->id]);

        // Mise à jour valide
        $response = $this->putJson("/api/v1/tasks/{$task->id}", ['title' => 'Updated Title']);
        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Title');

        // Essaye de modifier une tâche d'un autre utilisateur
        $otherUser = User::factory()->create(['role' => 'user']);
        $otherTask = Task::factory()->create(['assigned_to' => $otherUser->id]);

        $response = $this->putJson("/api/v1/tasks/{$otherTask->id}", ['title' => 'Hack']);
        $response->assertStatus(403);
    }

    public function test_delete_own_task_and_forbidden_other()
    {
        $user = $this->actingUser();
        $task = Task::factory()->create(['assigned_to' => $user->id]);

        // Suppression réussie
        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Task supprimée avec succès.']);

        // Essaye de supprimer une tâche d'un autre utilisateur
        $otherUser = User::factory()->create(['role' => 'user']);
        $otherTask = Task::factory()->create(['assigned_to' => $otherUser->id]);

        $response = $this->deleteJson("/api/v1/tasks/{$otherTask->id}");
        $response->assertStatus(403);
    }
}
