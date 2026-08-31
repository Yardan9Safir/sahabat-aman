<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curhats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tujuan', ['teman', 'konselor']);
            $table->enum('kategori', ['pribadi', 'sosial', 'belajar', 'karier']);
            $table->text('pesan');
            $table->boolean('anonim')->default(true);
            $table->enum('status', ['terkirim', 'dilihat', 'dibalas', 'ditangani'])->default('terkirim');
            $table->timestamp('dilihat_at')->nullable();
            $table->foreignId('ditangani_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditangani_at')->nullable();
            $table->timestamps();
        });

        Schema::create('balasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curhat_id')->constrained('curhats')->cascadeOnDelete();
            $table->foreignId('pengirim_id')->constrained('users')->cascadeOnDelete();
            $table->enum('pengirim_role', ['teman', 'konselor']);
            $table->text('isi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balasans');
        Schema::dropIfExists('curhats');
    }
};
