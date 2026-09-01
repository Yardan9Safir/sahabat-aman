<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Siswa Demo 1',
            'email' => 'siswa1@sahabataman.test',
            'role' => 'siswa',
            'identifier' => 'SIS-2024-001',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Siswa Demo 2',
            'email' => 'siswa2@sahabataman.test',
            'role' => 'siswa',
            'identifier' => 'SIS-2024-002',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Siswa Demo 3',
            'email' => 'siswa3@sahabataman.test',
            'role' => 'siswa',
            'identifier' => 'SIS-2024-003',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Guru BK Demo',
            'email' => 'guru@sahabataman.test',
            'role' => 'guru',
            'identifier' => 'BK-001',
            'password' => bcrypt('password'),
        ]);
    }
}
