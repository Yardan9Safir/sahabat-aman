<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('curhat', function (Blueprint $table) {
            $table->enum('tujuan', ['guru'])->change();
        });

        Schema::table('balasan', function (Blueprint $table) {
            $table->enum('pengirim_role', ['siswa', 'guru'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curhat', function (Blueprint $table) {
            $table->enum('tujuan', ['teman', 'konselor'])->change();
        });

        Schema::table('balasan', function (Blueprint $table) {
            $table->enum('pengirim_role', ['siswa', 'teman', 'konselor'])->change();
        });
    }
};
