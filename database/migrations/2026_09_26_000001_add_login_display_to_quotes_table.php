<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->boolean('tampil_di_login')->default(false)->after('is_daily');
            $table->unsignedTinyInteger('urutan_login')->nullable()->after('tampil_di_login');

            $table->index(['tampil_di_login', 'urutan_login']);
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropIndex(['tampil_di_login', 'urutan_login']);
            $table->dropColumn(['tampil_di_login', 'urutan_login']);
        });
    }
};
