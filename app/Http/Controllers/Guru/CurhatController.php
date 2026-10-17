<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Concerns\SummarizesCurhats;
use App\Http\Controllers\Controller;
use App\Models\Curhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurhatController extends Controller
{
    use SummarizesCurhats;

    /**
     * Kotak masuk curhat siswa untuk Guru BK: curhat yang ditujukan kepadanya
     * (kelas yang diampu) plus curhat siswa ke konselor sebaya bimbingannya
     * (dipantau, hanya baca).
     */
    public function index(Request $request)
    {
        ['kategori' => $kategori, 'status' => $status] = $this->curhatFilters($request);

        $curhatsKeGuru = Curhat::query()->terlihatGuru($request->user());

        $totalMasuk = (clone $curhatsKeGuru)
            ->where('status', Curhat::STATUS_TERKIRIM)
            ->count();

        $ringkasan = $this->summarizeCurhats(
            $curhatsKeGuru,
            $kategori,
            $status,
            ['siswa', 'penerima', 'balasans', 'penangan'],
        );

        return view('admin.index', [
            'curhats' => $ringkasan['curhats'],
            'jumlahKategori' => $ringkasan['jumlahKategori'],
            'jumlahStatus' => $ringkasan['jumlahStatus'],
            'kategori' => $kategori,
            'status' => $status,
            'totalMasuk' => $totalMasuk,
        ]);
    }

    /**
     * Detail percakapan curhat yang masuk ke BK. Curhat milik konselor sebaya
     * bimbingannya hanya bisa dipantau (baca), bukan dibalas.
     */
    public function show(Curhat $curhat)
    {
        $guru = Auth::user();

        abort_unless(Curhat::whereKey($curhat->id)->terlihatGuru($guru)->exists(), 403);

        $curhat->load(['siswa', 'penerima', 'balasans.pengirim', 'penangan']);

        if ($curhat->penerima_id === Auth::id()) {
            $curhat->setViewed();
        }

        return view('admin.show', compact('curhat'));
    }

    public function reply(Curhat $curhat, Request $request)
    {
        $guru = Auth::user();

        abort_unless($curhat->penerima_id === $guru->id, 403);
        abort_unless($guru->handlesKelas($curhat->siswa?->kelas), 403);
        abort_if($curhat->isDitangani(), 403);

        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        $balasan = $curhat->balasans()->create([
            'pengirim_id' => $guru->id,
            'pengirim_role' => $guru->role,
            'isi' => $validated['isi'],
        ]);

        $curhat->setReplied();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'balasan' => [
                    'id' => $balasan->id,
                    'isi' => $balasan->isi,
                    'pengirim_name' => $guru->name,
                    'pengirim_role' => $guru->role,
                    'pengirim_role_label' => $guru->roleLabel(),
                    'created_at' => $balasan->created_at->translatedFormat('d M Y, H:i'),
                    'created_time' => $balasan->created_at->translatedFormat('H:i'),
                    'is_me' => true,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function handle(Curhat $curhat, Request $request)
    {
        $guru = Auth::user();

        abort_unless($curhat->penerima_id === $guru->id, 403);
        abort_unless($guru->handlesKelas($curhat->siswa?->kelas), 403);

        $curhat->update([
            'status' => Curhat::STATUS_DITANGANI,
            'ditangani_by' => $guru->id,
            'ditangani_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('guru.curhat.index')
            ->with('success', 'Curhat telah ditangani dan riwayat chat telah dihapus.');
    }

    public function destroy(Curhat $curhat)
    {
        $guru = Auth::user();

        // Guru boleh hapus riwayat yang terlihat olehnya: chat miliknya sendiri
        // maupun chat konselor sebaya bimbingannya (riwayat hanya di guru BK).
        abort_unless(Curhat::whereKey($curhat->id)->terlihatGuru($guru)->exists(), 403);

        $curhat->balasans()->delete();
        $curhat->delete();

        return redirect()->route('guru.curhat.index')
            ->with('success', 'Riwayat curhat berhasil dihapus permanen.');
    }
}
