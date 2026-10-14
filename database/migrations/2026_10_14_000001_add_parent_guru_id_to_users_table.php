<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Guru BK yang menjadi parent (pembimbing) seorang konselor sebaya.
            $table->foreignId('parent_guru_id')->nullable()->after('guru_kelas')
                ->constrained('users')->nullOnDelete();
        });

        // Backfill 10 konselor bawaan: A&B → guru 1, CD → guru 2, EF → guru 3, GH → guru 4, IJ → guru 5.
        foreach (str_split('ABCDEFGHIJ') as $i => $huruf) {
            $guruId = User::where('role', User::ROLE_GURU)
                ->where('identifier', 'GURU-0'.(intdiv($i, 2) + 1))
                ->value('id');
            if ($guruId) {
                User::where('identifier', "KON-{$huruf}")->update(['parent_guru_id' => $guruId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_guru_id');
        });
    }
};
