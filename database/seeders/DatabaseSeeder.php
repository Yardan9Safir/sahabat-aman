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
        // 2 akun siswa
        User::factory()->create([
            'name' => 'Udin',
            'email' => 'udin@gmail.com',
            'role' => 'siswa',
            'identifier' => 'SIS-0001',
            'kelas' => '7A',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Adit',
            'email' => 'adit@gmail.com',
            'role' => 'siswa',
            'identifier' => 'SIS-0002',
            'kelas' => '8B',
            'password' => bcrypt('password'),
        ]);

        // 5 guru BK — masing-masing hanya mengampu kelas tertentu.
        $guruBk = [
            ['Anna Koiria, S.Psi', 'anna@gmail.com', 'GURU-01', ['7A', '7B', '7C', '7D', '7E', '7F']],
            ['Annisa Uswatun Hasanah, S.Pd', 'annisa@gmail.com', 'GURU-02', ['7J', '8A', '8B', '8C', '8D', '8E']],
            ['Haidarratur Rochma, S.Pd', 'haidarra@gmail.com', 'GURU-03', ['7G', '8F', '8G', '8H', '8I', '8J']],
            ['Erna Krisnawati, S.Pd', 'erna@gmail.com', 'GURU-04', ['7H', '7I', '9A', '9B', '9C', '9D', '9E']],
            ['Dian Miftahul Falah, S.Psi', 'dian@gmail.com', 'GURU-05', ['9F', '9G', '9H', '9I', '9J', '9K']],
        ];

        foreach ($guruBk as [$name, $email, $identifier, $kelas]) {
            User::factory()->create([
                'name' => $name,
                'email' => $email,
                'role' => 'guru',
                'identifier' => $identifier,
                'kelas' => '',
                'guru_kelas' => $kelas,
                'password' => bcrypt('password'),
            ]);
        }

        // 10 konselor sebaya — KonselorA@… sampai KonselorJ@… (batas maksimal).
        // Konselor tidak terikat kelas (kolom `kelas` kosong), semua siswa bisa memilih mereka.
        foreach (str_split('ABCDEFGHIJ') as $huruf) {
            User::factory()->create([
                'name' => "Konselor {$huruf}",
                'email' => "Konselor{$huruf}@gmail.com",
                'role' => 'konselor sebaya',
                'identifier' => "KON-{$huruf}",
                'kelas' => '',
                'password' => bcrypt('password'),
            ]);
        }

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'identifier' => 'ADM-01',
            'kelas' => '',
            'password' => bcrypt('password'),
        ]);
    }
}
