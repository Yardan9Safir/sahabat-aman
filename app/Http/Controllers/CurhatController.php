<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurhatController extends Controller
{
    public function landing()
    {
        $quotes = Quote::untukBeranda();

        return view('home', compact('quotes'));
    }

    public function createForm()
    {
        abort_unless(Auth::user()->role === User::ROLE_SISWA, 403);

        $gurus = User::where('role', User::ROLE_GURU)->orderBy('name')->get();
        $konselors = User::where('role', User::ROLE_KONSELOR_SEBAYA)->orderBy('name')->get();

        return view('curhat.create', compact('gurus', 'konselors'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->role === User::ROLE_SISWA, 403);

        $validated = $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'kategori' => ['required', 'in:pribadi,sosial,belajar,karier'],
            'pesan' => ['required', 'string', 'min:10'],
        ]);

        $penerima = User::findOrFail($validated['penerima_id']);

        $curhat = $request->user()->curhats()->create([
            'siswa_id' => $request->user()->id,
            'nama_pengirim' => $request->user()->name,
            'penerima_id' => $penerima->id,
            'tujuan' => $penerima->role,
            'nama_penerima' => $penerima->name,
            'kategori' => $validated['kategori'],
            'status' => 'terkirim',
        ]);

        $curhat->balasans()->create([
            'pengirim_id' => $request->user()->id,
            'pengirim_role' => $request->user()->role,
            'isi' => $validated['pesan'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Curhatmu terkirim.');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            return redirect()->route('admin.index');
        }

        $query = Curhat::query()
            ->with('balasans')
            ->where(function ($q) use ($user) {
                $q->where('penerima_id', $user->id)
                    ->orWhere('siswa_id', $user->id);
            });

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
            abort_unless($curhat->tujuan === 'guru', 403);

            return redirect()->route('admin.show', $curhat);
        }

        abort_unless($curhat->penerima_id === $user->id || $curhat->siswa_id === $user->id, 403);

        if ($curhat->penerima_id === $user->id) {
            $curhat->setViewed();
        }

        return view('dashboard.show', compact('curhat'));
    }

    public function reply(Curhat $curhat, Request $request)
    {
        $user = Auth::user();

        $isPenerima = in_array($user->role, [User::ROLE_GURU, User::ROLE_KONSELOR_SEBAYA], true)
            && $curhat->penerima_id === $user->id;

        $isSiswaOwns = $user->role === User::ROLE_SISWA && $curhat->siswa_id === $user->id;

        abort_unless($isPenerima || $isSiswaOwns, 403);
        abort_if($curhat->status === 'ditangani', 403);

        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        $balasan = $curhat->balasans()->create([
            'pengirim_id' => $user->id,
            'pengirim_role' => $user->role,
            'isi' => $validated['isi'],
        ]);

        if ($isPenerima) {
            $curhat->setReplied();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'balasan' => [
                    'id' => $balasan->id,
                    'isi' => $balasan->isi,
                    'pengirim_name' => $user->name,
                    'pengirim_role' => $user->role,
                    'pengirim_role_label' => $user->roleLabel(),
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
        $user = Auth::user();

        abort_unless(
            in_array($user->role, [User::ROLE_GURU, User::ROLE_KONSELOR_SEBAYA], true)
                && $curhat->penerima_id === $user->id,
            403
        );

        $curhat->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        $target = $user->role === User::ROLE_GURU ? 'admin.index' : 'dashboard';

        return redirect()->route($target)
            ->with('success', 'Curhat telah ditangani dan riwayat chat telah dihapus.');
    }
}
