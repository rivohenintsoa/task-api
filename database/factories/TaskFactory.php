<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['todo', 'in-progress', 'done']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'assigned_to' => $user->id,
            'created_by' => $user->id,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
