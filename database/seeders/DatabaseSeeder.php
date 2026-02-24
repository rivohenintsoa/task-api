<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password')
        ]);

        // 4 users normaux
        $users = User::factory(4)->create();

        // 20 tâches assignées aux users existants
        Task::factory(20)->create();
    }
}
