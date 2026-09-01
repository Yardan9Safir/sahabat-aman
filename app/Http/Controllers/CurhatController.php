<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurhatController extends Controller
{
    /**
     * Halaman landing
     */
    public function landing()
    {
        return view('home');
    }

    /**
     * Form untuk membuat curhat baru
     */
    public function createForm()
    {
        $kategoris = Kategori::all();
        return view('curhat.create', compact('kategoris'));
    }

    /**
     * Simpan curhat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tujuan' => ['required', 'in:guru'],
            'nama_penerima' => ['nullable', 'string', 'max:255'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'pesan' => ['required', 'string', 'min:10'],
            'anonim' => ['nullable', 'boolean'],
        ]);

        $request->user()->curhats()->create([
            'tujuan' => $validated['tujuan'],
            'nama_penerima' => $validated['nama_penerima'] ?? null,
            'kategori_id' => $validated['kategori_id'],
            'pesan' => $validated['pesan'],
            'anonim' => $request->boolean('anonim'),
            'status' => 'terkirim',
        ]);

        return redirect()->route('riwayat')->with('success', 'Curhatmu terkirim.');
    }

    /**
     * Daftar riwayat curhat siswa
     */
    public function history()
    {
        $curhats = Auth::user()->curhats()->latest()->get();

        return view('riwayat.index', compact('curhats'));
    }

    /**
     * Detail curhat siswa
     */
    public function detail(Curhat $curhat)
    {
        abort_unless(Auth::id() === $curhat->siswa_id, 403);

        $curhat->setViewed();

        return view('riwayat.show', compact('curhat'));
    }
}
