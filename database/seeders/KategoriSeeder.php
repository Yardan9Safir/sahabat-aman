<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::firstOrCreate(
            ['slug' => 'pribadi'],
            ['nama' => 'Pribadi', 'deskripsi' => 'Masalah personal dan kepribadian', 'icon' => '👤']
        );

        Kategori::firstOrCreate(
            ['slug' => 'sosial'],
            ['nama' => 'Sosial', 'deskripsi' => 'Masalah hubungan dan pergaulan', 'icon' => '👥']
        );

        Kategori::firstOrCreate(
            ['slug' => 'belajar'],
            ['nama' => 'Belajar', 'deskripsi' => 'Masalah akademik dan pembelajaran', 'icon' => '📚']
        );

        Kategori::firstOrCreate(
            ['slug' => 'karier'],
            ['nama' => 'Karier', 'deskripsi' => 'Masalah karir dan masa depan', 'icon' => '🎯']
        );
    }
}
