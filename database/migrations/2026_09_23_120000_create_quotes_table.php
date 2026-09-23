<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id('id_quotes');
            $table->foreignId('id_pembuat')->constrained('users')->cascadeOnDelete();
            $table->string('judul_quotes');
            $table->enum('category_quotes', ['motivasi', 'pribadi', 'sosial', 'belajar', 'karier']);
            $table->text('isi_quotes');
            $table->boolean('is_daily')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};