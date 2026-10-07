<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Curhat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    /**
     * Rekapitulasi curhat masuk: per kategori, per status, dan per bulan.
     * Hanya curhat dari kelas yang diampu guru tersebut.
     * TODO: ekspor CSV/Excel.
     */
    public function index()
    {
        $dasar = Curhat::query()
            ->where('tujuan', User::ROLE_GURU)
            ->dariKelas(Auth::user()->assignedKelas());

        $perKategori = (clone $dasar)
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $perStatus = (clone $dasar)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $perBulan = (clone $dasar)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $rekap = [
            'total' => (clone $dasar)->count(),
            'perKategori' => $perKategori,
            'perStatus' => $perStatus,
            'perBulan' => $perBulan,
        ];

        return view('guru.rekap', compact('rekap'));
    }
}
