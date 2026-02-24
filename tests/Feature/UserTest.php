<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crée un utilisateur et l'authentifie pour les tests.
     */
    protected function actingUser($role = 'user')
    {
        $user = User::factory()->create(['role' => $role]);
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    /**
     * Test que la liste des utilisateurs est récupérée correctement.
     */
    public function test_get_users_list()
    {
        User::factory()->count(3)->create();
        $this->actingUser();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email', 'role']
                     ]
                 ]);

        $this->assertCount(4, $response->json('data'));
    }

    /**
     * Test que l'accès sans authentification est interdit.
     */
    public function test_get_users_unauthenticated()
    {
        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(401);
    }
}