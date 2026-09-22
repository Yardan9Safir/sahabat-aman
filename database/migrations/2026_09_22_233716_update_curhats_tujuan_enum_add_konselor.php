<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE curhats MODIFY COLUMN tujuan ENUM('siswa', 'guru', 'konselor') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE curhats MODIFY COLUMN tujuan ENUM('siswa', 'guru') NOT NULL");
        }
    }
};