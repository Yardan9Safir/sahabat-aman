<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Daftar curhat untuk teman/konselor
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            return redirect('/curhat');
        }

        $query = Curhat::query()->where('tujuan', $user->role === 'teman' ? 'teman' : 'konselor');

        if (request('kategori_id')) {
            $query->where('kategori_id', request('kategori_id'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $curhats = $query->latest()->get();
        $kategoris = Kategori::all();

        return view('dashboard.index', compact('curhats', 'kategoris'));
    }

    /**
     * Detail curhat untuk teman/konselor
     */
    public function show(Curhat $curhat)
    {
        $user = Auth::user();

        if ($user->role === 'teman' && $curhat->tujuan !== 'teman') {
            abort(403);
        }

        if ($user->role === 'konselor' && $curhat->tujuan !== 'konselor') {
            abort(403);
        }

        $curhat->setViewed();

        return view('dashboard.show', compact('curhat'));
    }

    /**
     * Tandai curhat sebagai selesai (hanya guru)
     */
    public function handle(Curhat $curhat, Request $request)
    {
        $user = Auth::user();

        abort_unless($user->role === 'guru', 403);
        abort_unless($curhat->tujuan === 'guru', 403);

        $curhat->setHandled($user);

        return redirect()->route('dashboard')->with('success', 'Curhat telah ditandai selesai.');
    }
}
