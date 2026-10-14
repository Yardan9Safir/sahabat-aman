<?php

namespace App\Http\Controllers\KonselorSebaya;

use App\Http\Controllers\Controller;
use App\Models\Curhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiPendampinganController extends Controller
{
    /**
     * Detail sesi pendampingan (percakapan) milik konselor sebaya.
     */
    public function show(Curhat $curhat)
    {
        $konselor = Auth::user();
        abort_unless($konselor->isKonselorSebaya(), 403);

        abort_unless(
            $curhat->penerima_id === $konselor->id
            || $curhat->siswa_id === $konselor->id
            || ($konselor->parent_guru_id && $curhat->penerima_id === $konselor->parent_guru_id),
            403
        );

        if ($curhat->penerima_id === $konselor->id) {
            $curhat->setViewed();
        }

        return view('dashboard.show', compact('curhat'));
    }

    public function reply(Curhat $curhat, Request $request)
    {
        $konselor = Auth::user();

        $isPengirim = $curhat->siswa_id === $konselor->id;
        $isPenangani = $konselor->bisaTanganiCurhat($curhat);

        abort_unless($isPengirim || $isPenangani, 403);
        abort_if($curhat->isDitangani(), 403);

        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        $balasan = $curhat->balasans()->create([
            'pengirim_id' => $konselor->id,
            'pengirim_role' => $konselor->role,
            'isi' => $validated['isi'],
        ]);

        if ($isPenangani) {
            $curhat->setReplied();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'balasan' => [
                    'id' => $balasan->id,
                    'isi' => $balasan->isi,
                    'pengirim_name' => $konselor->name,
                    'pengirim_role' => $konselor->role,
                    'pengirim_role_label' => $konselor->roleLabel(),
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
        $konselor = Auth::user();

        abort_unless($konselor->bisaTanganiCurhat($curhat), 403);

        $curhat->update([
            'status' => Curhat::STATUS_DITANGANI,
            'ditangani_by' => $konselor->id,
            'ditangani_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('konselor.dashboard')
            ->with('success', 'Curhat telah ditangani dan riwayat chat telah dihapus.');
    }

    public function destroy(Curhat $curhat)
    {
        $konselor = Auth::user();

        abort_unless($konselor->isKonselorSebaya() && $curhat->penerima_id === $konselor->id, 403);

        $curhat->balasans()->delete();
        $curhat->delete();

        return redirect()->route('konselor.dashboard')
            ->with('success', 'Riwayat curhat berhasil dihapus permanen.');
    }
}
