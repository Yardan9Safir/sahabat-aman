<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Kelola kelas yang diampu setiap guru BK.
     */
    public function index()
    {
        $gurus = User::query()
            ->where('role', User::ROLE_GURU)
            ->orderBy('name')
            ->get();

        return view('admin.kelas', [
            'gurus' => $gurus,
            'semuaKelas' => User::ALL_KELAS,
        ]);
    }

    /**
     * Simpan daftar kelas yang diampu satu guru.
     */
    public function update(Request $request, User $user)
    {
        abort_unless($user->isGuru(), 403);

        $validated = $request->validate([
            'kelas' => ['nullable', 'array', 'max:'.count(User::ALL_KELAS)],
            'kelas.*' => ['string', 'in:'.implode(',', User::ALL_KELAS)],
        ]);

        $user->update([
            'guru_kelas' => array_values($validated['kelas'] ?? []),
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas yang diampu {$user->name} berhasil diperbarui.");
    }
}
