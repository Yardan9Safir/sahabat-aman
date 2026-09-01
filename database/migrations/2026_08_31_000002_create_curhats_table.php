<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curhat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tujuan', ['teman', 'konselor']);
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->text('pesan');
            $table->boolean('anonim')->default(true);
            $table->enum('status', ['terkirim', 'dilihat', 'dibalas', 'ditangani'])->default('terkirim');
            $table->timestamp('dilihat_at')->nullable();
            $table->foreignId('ditangani_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditangani_at')->nullable();
            $table->timestamps();
        });

        Schema::create('balasan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('curhat_id');
            $table->foreign('curhat_id')->references('id')->on('curhat')->cascadeOnDelete();
            $table->foreignId('pengirim_id')->constrained('users')->cascadeOnDelete();
            $table->enum('pengirim_role', ['teman', 'konselor']);
            $table->text('isi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balasan');
        Schema::dropIfExists('curhat');
    }
};
