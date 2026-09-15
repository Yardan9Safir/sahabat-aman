<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 3 akun utama
        User::factory()->create([
            'name' => 'Udin',
            'email' => 'udin@gmail.com',
            'role' => 'siswa',
            'identifier' => 'SIS-0001',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Adit',
            'email' => 'adit@gmail.com',
            'role' => 'siswa',
            'identifier' => 'SIS-0002',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Guru Demo',
            'email' => 'guru@sahabataman.test',
            'role' => 'guru',
            'identifier' => 'GRU-001',
            'password' => bcrypt('password'),
        ]);

        // // 998 siswa tambahan — bulk insert
        // $siswaRows = [];
        // for ($i = 0; $i < 998; $i++) {
        //     $siswaRows[] = [
        //         'name' => fake()->name(),
        //         'email' => "siswa{$i}@" . fake()->safeEmailDomain(),
        //         'role' => 'siswa',
        //         'identifier' => 'SIS-' . str_pad($i + 3, 4, '0', STR_PAD_LEFT),
        //         'password' => Hash::make('password'),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ];
        // }
        // $this->command->info('Menyisipkan 998 siswa...');
        // $this->chunkInsert($siswaRows);

        // // 100 guru tambahan — bulk insert
        // $guruRows = [];
        // for ($i = 0; $i < 100; $i++) {
        //     $guruRows[] = [
        //         'name' => fake()->name(),
        //         'email' => "guru{$i}@" . fake()->safeEmailDomain(),
        //         'role' => 'guru',
        //         'identifier' => 'GRU-' . str_pad($i + 2, 3, '0', STR_PAD_LEFT),
        //         'password' => Hash::make('password'),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ];
        // }
        // $this->command->info('Menyisipkan 100 guru...');
        // $this->chunkInsert($guruRows);

        // $total = User::count();
        // $this->command->info("Selesai! Total user: {$total}");
    }

    // private function chunkInsert(array $rows): void
    // {
    //     $chunks = array_chunk($rows, 200);
    //     foreach ($chunks as $chunk) {
    //         \Illuminate\Support\Facades\DB::table('users')->insert($chunk);
    //     }
    // }
}