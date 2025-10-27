<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'cash' => 600000,
            'password' => 'password', // Plain password for testing
        ]);
    }
}
