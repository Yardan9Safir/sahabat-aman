<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalasanController extends Controller
{
    /**
     * Balas curhat (guru) atau balas balasan (siswa)
     */
    public function store(Curhat $curhat, Request $request)
    {
        $user = Auth::user();

        // Jika siswa
        if ($user->role === 'siswa') {
            // Pastikan siswa adalah pembuat curhat
            if ($curhat->siswa_id !== $user->id) {
                abort(403);
            }
            // Pastikan sudah ada balasan
            if ($curhat->balasans()->count() === 0) {
                abort(403);
            }
        } else {
            // Jika guru
            abort_unless($user->role === 'guru', 403);
            abort_unless($curhat->tujuan === 'guru', 403);
        }

        // Validasi input
        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        // Buat balasan
        $curhat->balasans()->create([
            'pengirim_id' => $user->id,
            'pengirim_role' => $user->role,
            'isi' => $validated['isi'],
        ]);

        // Update status curhat jika dari guru
        if ($user->role !== 'siswa') {
            $curhat->setReplied();
        }

        // Redirect sesuai role
        $route = $user->role === 'siswa' ? 'riwayat.show' : 'dashboard.show';
        return redirect()->route($route, $curhat)->with('success', 'Balasan berhasil dikirim.');
    }
}
