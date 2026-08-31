<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurhatController extends Controller
{
    public function landing()
    {
        return view('home');
    }

    public function createForm()
    {
        return view('curhat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tujuan' => ['required', 'in:teman,konselor'],
            'nama_penerima' => ['nullable', 'string', 'max:255'],
            'kategori' => ['required', 'in:pribadi,sosial,belajar,karier'],
            'pesan' => ['required', 'string', 'min:10'],
            'anonim' => ['nullable', 'boolean'],
        ]);

        $request->user()->curhats()->create([
            'tujuan' => $validated['tujuan'],
            'nama_penerima' => $validated['nama_penerima'] ?? null,
            'kategori' => $validated['kategori'],
            'pesan' => $validated['pesan'],
            'anonim' => $request->boolean('anonim'),
            'status' => 'terkirim',
        ]);

        return redirect()->route('riwayat')->with('success', 'Curhatmu terkirim.');
    }

    public function history()
    {
        $curhats = Auth::user()->curhats()->latest()->get();

        return view('riwayat.index', compact('curhats'));
    }

    public function detail(Curhat $curhat)
    {
        abort_unless(Auth::id() === $curhat->siswa_id, 403);

        $curhat->setViewed();

        return view('riwayat.show', compact('curhat'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            return redirect('/curhat');
        }

        $query = Curhat::query()->where('tujuan', $user->role === 'teman' ? 'teman' : 'konselor');

        if (request('kategori')) {
            $query->where('kategori', request('kategori'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $curhats = $query->latest()->get();

        return view('dashboard.index', compact('curhats'));
    }

    public function recipientDetail(Curhat $curhat)
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

    public function reply(Curhat $curhat, Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            abort(403);
        }

        if ($user->role === 'teman' && $curhat->tujuan !== 'teman') {
            abort(403);
        }

        if ($user->role === 'konselor' && $curhat->tujuan !== 'konselor') {
            abort(403);
        }

        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        $curhat->balasans()->create([
            'pengirim_id' => $user->id,
            'pengirim_role' => $user->role,
            'isi' => $validated['isi'],
        ]);

        $curhat->setReplied();

        return redirect()->route('dashboard')->with('success', 'Balasan berhasil dikirim.');
    }

    public function handle(Curhat $curhat)
    {
        $user = Auth::user();

        abort_unless($user->role === 'konselor', 403);
        abort_if($curhat->tujuan !== 'konselor', 403);

        $curhat->setHandled($user);

        return redirect()->route('dashboard')->with('success', 'Curhat telah ditandai selesai.');
    }
}
