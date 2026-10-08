<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KelasController extends Controller
{
    /**
     * Kelola kelas yang diampu setiap guru BK.
     */
    public function index(Request $request)
    {
        $gurus = User::query()
            ->where('role', User::ROLE_GURU)
            ->orderBy('name')
            ->get();

        $terpilih = $gurus->firstWhere('id', $request->query('guru')) ?? $gurus->first();

        // Kelas yang diampu guru lain (guru terpilih dibebaskan dari daftar ini).
        $pemilik = [];
        foreach ($gurus as $guru) {
            if ($guru->id === $terpilih?->id) {
                continue;
            }
            foreach ($guru->assignedKelas() as $kelas) {
                $pemilik[$kelas] = $guru->name;
            }
        }

        $diampu = 0;
        foreach ($gurus as $guru) {
            $diampu += count($guru->assignedKelas());
        }

        return view('admin.kelas', [
            'gurus' => $gurus,
            'terpilih' => $terpilih,
            'semuaKelas' => User::ALL_KELAS,
            'pemilik' => $pemilik,
            'statistik' => [
                'guru' => $gurus->count(),
                'rombel' => count(User::ALL_KELAS),
                'kosong' => count(User::ALL_KELAS) - $diampu,
                'persen' => count(User::ALL_KELAS)
                    ? (int) round($diampu / count(User::ALL_KELAS) * 100)
                    : 0,
            ],
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

        $kelasTerpilih = array_values($validated['kelas'] ?? []);

        // Satu kelas hanya boleh diampu satu guru.
        if ($pesan = User::validasiGuruKelas($kelasTerpilih, $user->id)) {
            throw ValidationException::withMessages(['kelas' => $pesan]);
        }

        $user->update([
            'guru_kelas' => $kelasTerpilih,
        ]);

        return redirect()->route('admin.kelas.index', ['guru' => $user->id])
            ->with('success', "Kelas yang diampu {$user->name} berhasil diperbarui.");
    }
}
