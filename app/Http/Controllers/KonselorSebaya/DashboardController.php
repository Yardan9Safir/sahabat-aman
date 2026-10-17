<?php

namespace App\Http\Controllers\KonselorSebaya;

use App\Http\Controllers\Concerns\SummarizesCurhats;
use App\Http\Controllers\Controller;
use App\Models\Curhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use SummarizesCurhats;

    /**
     * Kotak masuk konselor sebaya: curhat yang ditujukan kepadanya
     * plus curhat yang ia kirim sendiri sebagai siswa, plus curhat yang
     * masuk ke guru parent-nya (dibagikan, hanya bisa dilihat).
     * Riwayat (sudah ditangani) tidak ditampilkan — riwayat hanya di guru BK.
     */
    public function index(Request $request)
    {
        $konselor = Auth::user();
        abort_unless($konselor->isKonselorSebaya(), 403);

        ['kategori' => $kategori, 'status' => $status] = $this->curhatFilters($request);

        $curhatsSaya = Curhat::query()
            ->where(function ($query) use ($konselor) {
                $query->where('penerima_id', $konselor->id)
                    ->orWhere('siswa_id', $konselor->id);

                if ($konselor->parent_guru_id) {
                    $query->orWhere('penerima_id', $konselor->parent_guru_id);
                }
            })
            ->where('status', '!=', Curhat::STATUS_DITANGANI);

        $ringkasan = $this->summarizeCurhats($curhatsSaya, $kategori, $status, ['balasans', 'penangan']);

        return view('dashboard.index', [
            'curhats' => $ringkasan['curhats'],
            'jumlahKategori' => $ringkasan['jumlahKategori'],
            'jumlahStatus' => $ringkasan['jumlahStatus'],
            'kategori' => $kategori,
            'status' => $status,
        ]);
    }
}
