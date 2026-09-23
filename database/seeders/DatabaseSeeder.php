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
        // 3 akun utama
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

        User::factory()->create([
            'name' => 'Guru BK',
            'email' => 'guru@gmail.com',
            'role' => 'guru',
            'identifier' => 'GRU-001',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Konselor Sebaya A',
            'email' => 'konselorA@gmail.com',
            'role' => 'konselor sebaya',
            'identifier' => 'KON-7A',
            'kelas' => '7A',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Konselor Sebaya B',
            'email' => 'konselorB@gmail.com',
            'role' => 'konselor sebaya',
            'identifier' => 'KON-7B',
            'kelas' => '7B',
            'password' => bcrypt('password'),
        ]);

        // $this->seedDemoCurhats();
    }

    // private function seedDemoCurhats(): void
    // {
    //     $udin = User::where('email', 'udin@gmail.com')->first();
    //     $adit = User::where('email', 'adit@gmail.com')->first();
    //     $guru = User::where('email', 'guru@sahabataman.test')->first();
    //     $konselorA = User::where('email', 'konselor@sahabataman.test')->first();
    //     $konselorB = User::where('email', 'konselor2@sahabataman.test')->first();

    //     if (! $udin || ! $adit || ! $guru || ! $konselorA || ! $konselorB) {
    //         return;
    //     }

    //     // Udin -> Guru Demo (dibalas)
    //     $c1 = Curhat::create([
    //         'siswa_id' => $udin->id,
    //         'penerima_id' => $guru->id,
    //         'tujuan' => 'guru',
    //         'nama_penerima' => $guru->name,
    //         'kategori' => 'sosial',
    //         'pesan' => 'Saya merasa cemas setiap kali mau presentasi di depan kelas.',
    //         'status' => 'dibalas',
    //         'dilihat_at' => now()->subDay(),
    //     ]);
    //     Balasan::create([
    //         'curhat_id' => $c1->id,
    //         'pengirim_id' => $guru->id,
    //         'pengirim_role' => 'guru',
    //         'isi' => 'Cemas saat presentasi itu wajar, Adit pernah merasakannya juga. Coba latihan perlahan di depan teman terdekat ya.',
    //     ]);

    //     // Udin -> Konselor Sebaya A (dibalas, ada balasan siswa)
    //     $c2 = Curhat::create([
    //         'siswa_id' => $udin->id,
    //         'penerima_id' => $konselorA->id,
    //         'tujuan' => 'konselor sebaya',
    //         'nama_penerima' => $konselorA->name,
    //         'kategori' => 'karier',
    //         'pesan' => 'Aku bingung memilih ekstrakurikuler yang sesuai minatku.',
    //         'status' => 'dibalas',
    //         'dilihat_at' => now(),
    //     ]);
    //     Balasan::create([
    //         'curhat_id' => $c2->id,
    //         'pengirim_id' => $konselorA->id,
    //         'pengirim_role' => 'konselor sebaya',
    //         'isi' => 'Ceritakan dulu apa saja yang kamu suka dan tidak suka, biar bisa kita cari sama-sama.',
    //     ]);
    //     Balasan::create([
    //         'curhat_id' => $c2->id,
    //         'pengirim_id' => $udin->id,
    //         'pengirim_role' => 'siswa',
    //         'isi' => 'Aku suka menulis dan menggambar, tapi orang tuaku ingin aku ikut Pramuka.',
    //     ]);

    //     // Adit -> Guru Demo (sudah ditangani)
    //     $c3 = Curhat::create([
    //         'siswa_id' => $adit->id,
    //         'penerima_id' => $guru->id,
    //         'tujuan' => 'guru',
    //         'nama_penerima' => $guru->name,
    //         'kategori' => 'belajar',
    //         'pesan' => 'Saya sulit fokus belajar di rumah karena suasana rumah ramai.',
    //         'status' => 'ditangani',
    //         'dilihat_at' => now()->subDays(2),
    //         'ditangani_by' => $guru->id,
    //         'ditangani_at' => now()->subDay(),
    //     ]);
    //     Balasan::create([
    //         'curhat_id' => $c3->id,
    //         'pengirim_id' => $guru->id,
    //         'pengirim_role' => 'guru',
    //         'isi' => 'Kita bisa buat jadwal belajar di jam yang lebih tenang, misalnya pagi sebelum berangkat sekolah.',
    //     ]);

    //     // Adit -> Konselor Sebaya A (lancar, belum selesai)
    //     $c4 = Curhat::create([
    //         'siswa_id' => $adit->id,
    //         'penerima_id' => $konselorA->id,
    //         'tujuan' => 'konselor sebaya',
    //         'nama_penerima' => $konselorA->name,
    //         'kategori' => 'pribadi',
    //         'pesan' => 'Akhir-akhir ini perasaanku campur aduk dan tidak tahu harus cerita ke siapa.',
    //         'status' => 'dibalas',
    //         'dilihat_at' => now(),
    //     ]);
    //     Balasan::create([
    //         'curhat_id' => $c4->id,
    //         'pengirim_id' => $konselorA->id,
    //         'pengirim_role' => 'konselor sebaya',
    //         'isi' => 'Kamu sudah berani cerita di sini, itu langkah yang bagus. Coba gambarkan perasaanmu dengan kata-kata.',
    //     ]);

    //     // Adit -> Konselor Sebaya B (masih terkirim, belum dibalas)
    //     Curhat::create([
    //         'siswa_id' => $adit->id,
    //         'penerima_id' => $konselorB->id,
    //         'tujuan' => 'konselor sebaya',
    //         'nama_penerima' => $konselorB->name,
    //         'kategori' => 'sosial',
    //         'pesan' => 'Aku merasa canggung berteman dengan anak baru di kelas.',
    //         'status' => 'terkirim',
    //     ]);
    // }

        // // 998 siswa tambahan — bulk insert
        // $siswaRows = [];
        // for ($i = 0; $i < 998; $i++) {
        //     $siswaRows[] = [
        //         'name' => fake()->name(),
        //         'email' => "siswa{$i}@" . fake()->safeEmailDomain(),
        //         'role' => 'siswa',
        //         'identifier' => 'SIS-' . str_pad($i + 3, 4, '0', STR_PAD_LEFT),
        //         'kelas' => fake()->randomElement(['X-A', 'X-B', 'XI-A', 'XI-B', 'XII-A', 'XII-B']),
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

    // private function chunkInsert(array $rows): void
    // {
    //     $chunks = array_chunk($rows, 200);
    //     foreach ($chunks as $chunk) {
    //         \Illuminate\Support\Facades\DB::table('users')->insert($chunk);
    //     }
    // }
}
