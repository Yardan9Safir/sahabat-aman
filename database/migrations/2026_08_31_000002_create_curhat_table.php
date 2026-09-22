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
            $table->foreignId('penerima_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tujuan', ['siswa', 'guru', 'konselor']);
            $table->string('nama_penerima')->nullable();
            $table->enum('kategori', ['pribadi', 'sosial', 'belajar', 'karier']);
            $table->text('pesan');
            $table->enum('status', ['terkirim', 'dilihat', 'dibalas', 'ditangani'])->default('terkirim');
            $table->timestamp('dilihat_at')->nullable();
            $table->foreignId('ditangani_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditangani_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curhats');
    }
};
