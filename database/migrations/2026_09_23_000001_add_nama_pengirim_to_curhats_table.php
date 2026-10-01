<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('curhats', function (Blueprint $table) {
            $table->string('nama_pengirim')->nullable()->after('siswa_id');
        });
    }

    public function down(): void
    {
        Schema::table('curhats', function (Blueprint $table) {
            $table->dropColumn('nama_pengirim');
        });
    }
};