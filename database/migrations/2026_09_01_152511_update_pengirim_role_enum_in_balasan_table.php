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
        Schema::table('balasan', function (Blueprint $table) {
            $table->enum('pengirim_role', ['siswa', 'teman', 'konselor'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balasan', function (Blueprint $table) {
            $table->enum('pengirim_role', ['teman', 'konselor'])->change();
        });
    }
};
