<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(['email' => 'udin@gmail.com'], [
            'name' => 'Udin',
            'role' => 'siswa',
            'identifier' => 'SIS-2024-001',
            'kelas' => '7A',
            'password' => bcrypt('password'),
        ]);

        User::updateOrCreate(['email' => 'adit@gmail.com'], [
            'name' => 'Adit',
            'role' => 'siswa',
            'identifier' => 'SIS-2025-002',
            'kelas' => '7A',
            'password' => bcrypt('password'),
        ]);

        User::updateOrCreate(['email' => 'guru@sahabataman.test'], [
            'name' => 'Guru Demo',
            'role' => 'guru',
            'identifier' => 'GRU-001',
            'password' => bcrypt('password'),
        ]);

        $konselorData = [
            ['name' => 'Salsabila Putri', 'email' => 'konselor.7a.1@sahabataman.test', 'kelas' => '7A', 'anon_label' => 'A'],
            ['name' => 'Raka Prasetyo', 'email' => 'konselor.7a.2@sahabataman.test', 'kelas' => '7A', 'anon_label' => 'B'],
            ['name' => 'Nadia Zahra', 'email' => 'konselor.7b.1@sahabataman.test', 'kelas' => '7B', 'anon_label' => 'A'],
            ['name' => 'Bima Ardiansyah', 'email' => 'konselor.7b.2@sahabataman.test', 'kelas' => '7B', 'anon_label' => 'B'],
        ];

        foreach ($konselorData as $data) {
            User::updateOrCreate(['email' => $data['email']], [
                'name' => $data['name'],
                'role' => 'konselor',
                'identifier' => 'KSL-7-' . $data['kelas'] . '-' . $data['anon_label'],
                'kelas' => $data['kelas'],
                'anon_label' => $data['anon_label'],
                'password' => bcrypt('password'),
            ]);
        }
    }
}