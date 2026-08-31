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
            'name' => 'Siswa Demo',
            'email' => 'siswa@sahabataman.test',
            'role' => 'siswa',
            'identifier' => 'SIS-2024-001',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Teman Sebaya Demo',
            'email' => 'teman@sahabataman.test',
            'role' => 'teman',
            'identifier' => 'PEER-001',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Konselor Demo',
            'email' => 'konselor@sahabataman.test',
            'role' => 'konselor',
            'identifier' => 'BK-001',
            'password' => bcrypt('password'),
        ]);
    }
}
