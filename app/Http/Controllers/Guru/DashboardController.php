<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Curhat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Ikhtisar guru BK: kotak masuk curhat dari kelas yang diampu.
     */
    public function index()
    {

        $guru = Auth::user();

        // Termasuk curhat siswa ke konselor sebaya bimbingannya (dipantau).
        $masuk = Curhat::query()->terlihatGuru($guru);

        $perStatus = (clone $masuk)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statistik = [
            'masuk' => (clone $masuk)->count(),
            'belumDibalas' => (clone $masuk)->where('status', Curhat::STATUS_TERKIRIM)->count(),
            'dilihat' => (clone $masuk)->where('status', Curhat::STATUS_DILIHAT)->count(),
            'dibalas' => (clone $masuk)->where('status', Curhat::STATUS_DIBALAS)->count(),
            'ditangani' => (clone $masuk)->where('status', Curhat::STATUS_DITANGANI)->count(),
            'sayaPenerima' => Curhat::where('penerima_id', $guru->id)->count(),
            'perStatus' => $perStatus,
        ];

        $terbaru = (clone $masuk)
            ->with('siswa', 'penerima')
            ->latest()
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact('statistik', 'terbaru'));
    }
}
