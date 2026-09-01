<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Matikan foreign key check dulu biar tidak error saat truncate
        Schema::disableForeignKeyConstraints();

        DB::table('users')->truncate();
        DB::table('kategori')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->call([
            KategoriSeeder::class,
            UserSeeder::class,
        ]);
    }
}
