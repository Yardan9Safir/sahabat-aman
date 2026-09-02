<?php

namespace Database\Seeders;

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
        User::factory()->create([
            'name' => 'Udin',
            'email' => 'udin@gmail.com',
            'role' => 'siswa',
            'identifier' => 'SIS-2024-001',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Adit',
            'email' => 'adit@gmail.com',
            'role' => 'siswa',
            'identifier' => 'SIS-2025-002',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Guru Demo',
            'email' => 'guru@sahabataman.test',
            'role' => 'guru',
            'identifier' => 'GRU-001',
            'password' => bcrypt('password'),
        ]);
    }
}
