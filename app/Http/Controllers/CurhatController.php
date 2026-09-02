<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use App\Models\User;
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
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        $siswas = User::where('role', 'siswa')->where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('curhat.create', compact('gurus', 'siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'kategori' => ['required', 'in:pribadi,sosial,belajar,karier'],
            'pesan' => ['required', 'string', 'min:10'],
        ]);

        $penerima = User::findOrFail($validated['penerima_id']);

        $request->user()->curhats()->create([
            'penerima_id' => $penerima->id,
            'tujuan' => $penerima->role,
            'nama_penerima' => $penerima->name,
            'kategori' => $validated['kategori'],
            'pesan' => $validated['pesan'],
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

        return view('riwayat.show', compact('curhat'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            return redirect()->route('admin.index');
        }

        $query = Curhat::query()->where('penerima_id', $user->id);

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

        if ($user->role === 'guru') {
            return redirect()->route('admin.show', $curhat);
        }

        abort_unless($curhat->penerima_id === $user->id, 403);

        $curhat->setViewed();

        return view('dashboard.show', compact('curhat'));
    }

    public function reply(Curhat $curhat, Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $curhat->penerima_id === $user->id || $curhat->siswa_id === $user->id,
            403
        );

        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        $balasan = $curhat->balasans()->create([
            'pengirim_id' => $user->id,
            'pengirim_role' => $user->role,
            'isi' => $validated['isi'],
        ]);

        $curhat->setReplied();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'balasan' => [
                    'id' => $balasan->id,
                    'isi' => $balasan->isi,
                    'pengirim_name' => $user->name,
                    'pengirim_role' => $user->role,
                    'created_at' => $balasan->created_at->translatedFormat('d M Y, H:i'),
                    'is_me' => true,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function handle(Curhat $curhat, Request $request)
    {
        $user = Auth::user();

        abort_unless($curhat->penerima_id === $user->id, 403);

        $curhat->setHandled($user);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('dashboard')->with('success', 'Curhat telah ditandai selesai.');
    }
}
