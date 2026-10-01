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
            'kategori' => ['required', 'in:'.implode(',', Curhat::KATEGORI)],
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

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            return redirect()->route('admin.index');
        }

        $kategori = in_array($request->query('kategori'), Curhat::KATEGORI, true)
            ? $request->query('kategori')
            : null;

        $status = in_array($request->query('status'), Curhat::STATUS, true)
            ? $request->query('status')
            : null;

        $dasar = Curhat::query()
            ->where(function ($q) use ($user) {
                $q->where('penerima_id', $user->id)
                    ->orWhere('siswa_id', $user->id);
            });

        if ($user->role === User::ROLE_SISWA) {
            $dasar->where('status', '!=', 'ditangani');
        }

        $jumlahKategori = (clone $dasar)
            ->status($status)
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $jumlahStatus = (clone $dasar)
            ->kategori($kategori)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $curhats = (clone $dasar)
            ->kategori($kategori)
            ->status($status)
            ->with('balasans')
            ->latest()
            ->get();

        return view('dashboard.index', compact('curhats', 'kategori', 'status', 'jumlahKategori', 'jumlahStatus'));
    }

    public function recipientDetail(Curhat $curhat)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort_unless($curhat->tujuan === 'guru', 403);

            return redirect()->route('admin.show', $curhat);
        }

        if ($user->role === User::ROLE_SISWA && $curhat->status === 'ditangani') {
            return redirect()->route('dashboard')->with('error', 'Riwayat percakapan ini sudah ditutup.');
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

        $curhat->update([
            'status' => 'ditangani',
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        $target = $user->role === User::ROLE_GURU ? 'admin.index' : 'dashboard';

        return redirect()->route($target)
            ->with('success', 'Curhat telah ditangani dan riwayat chat telah dihapus.');
    }

    public function destroy(Curhat $curhat)
    {
        $user = Auth::user();

        // Pastikan hanya Guru atau Konselor yang berhak yang bisa menghapus
        abort_unless(
            in_array($user->role, [User::ROLE_GURU, User::ROLE_KONSELOR_SEBAYA], true)
                && $curhat->penerima_id === $user->id,
            403
        );

        // Hapus juga semua balasannya agar tidak jadi data yatim (orphan data) di database
        $curhat->balasans()->delete();

        // Hapus curhat utama
        $curhat->delete();

        $target = $user->role === User::ROLE_GURU ? 'admin.index' : 'dashboard';

        return redirect()->route($target)
            ->with('success', 'Riwayat curhat berhasil dihapus permanen.');
    }
}
