<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * Test de l'inscription réussie
     */
    public function test_register_success()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Hoby',
            'email' => 'hoby@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(201) // Doit retourner 201 Created
            ->assertJsonStructure([
                'data' => ['user' => ['id', 'name', 'email', 'role'], 'token']
            ]);
    }

    /** 
     * Test d'échec de l'inscription si la confirmation du mot de passe ne correspond pas
     */
    public function test_register_password_mismatch()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Hoby',
            'email' => 'hoby2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpass'
        ]);

        $response->assertStatus(422) // Doit retourner une erreur de validation
            ->assertJsonValidationErrors(['password']);
    }

    /** 
     * Test d'échec de l'inscription si l'email est déjà utilisé
     */
    public function test_register_email_taken()
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->postJson('/api/v1/register', [
            'name' => 'Hoby',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** 
     * Test de connexion réussie
     */
    public function test_login_success()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['user' => ['id', 'name', 'email', 'role'], 'token']
            ]);
    }

    /** 
     * Test de connexion échouée avec un mauvais email
     */
    public function test_login_wrong_email()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** 
     * Test de connexion échouée avec un mauvais mot de passe
     */
    public function test_login_wrong_password()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrongpass'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** 
     * Test de déconnexion sans être authentifié
     */
    public function test_logout_requires_auth()
    {
        $response = $this->postJson('/api/v1/logout');
        $response->assertStatus(401); // Non autorisé
    }

    /** 
     * Test de déconnexion réussie
     */
    public function test_logout_success()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        // Ajout de l'en-tête Authorization
        $this->withHeader('Authorization', 'Bearer ' . $token);

        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Déconnexion réussie.']);
    }
}