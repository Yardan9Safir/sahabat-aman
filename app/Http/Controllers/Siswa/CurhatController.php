<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Concerns\SummarizesCurhats;
use App\Http\Controllers\Controller;
use App\Models\Curhat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CurhatController extends Controller
{
    use SummarizesCurhats;

    /**
     * Riwayat curhat milik siswa (sudah ditangani tidak ditampilkan).
     */
    public function index(Request $request)
    {
        $siswa = Auth::user();
        abort_unless($siswa->isSiswa(), 403);

        ['kategori' => $kategori, 'status' => $status] = $this->curhatFilters($request);

        $curhatsSaya = Curhat::query()
            ->where(function ($query) use ($siswa) {
                $query->where('penerima_id', $siswa->id)
                    ->orWhere('siswa_id', $siswa->id);
            })
            ->where('status', '!=', Curhat::STATUS_DITANGANI);

        $ringkasan = $this->summarizeCurhats($curhatsSaya, $kategori, $status, ['balasans']);

        return view('dashboard.index', [
            'curhats' => $ringkasan['curhats'],
            'jumlahKategori' => $ringkasan['jumlahKategori'],
            'jumlahStatus' => $ringkasan['jumlahStatus'],
            'kategori' => $kategori,
            'status' => $status,
        ]);
    }

    public function create()
    {
        $siswa = Auth::user();
        abort_unless($siswa->isSiswa(), 403);

        // Guru BK hanya menerima curhat dari kelas yang diampunya.
        $gurus = User::where('role', User::ROLE_GURU)
            ->orderBy('name')
            ->get()
            ->filter(fn (User $guru) => $guru->handlesKelas($siswa->kelas))
            ->values();
        // Konselor sebaya hanya melayani kelasmu — tiap kelas punya 2 konselor.
        $konselors = User::where('role', User::ROLE_KONSELOR_SEBAYA)
            ->orderBy('name')
            ->get()
            ->filter(fn (User $konselor) => $konselor->handlesKelas($siswa->kelas))
            ->values();

        return view('curhat.create', compact('gurus', 'konselors'));
    }

    public function store(Request $request)
    {
        $siswa = $request->user();
        abort_unless($siswa->isSiswa(), 403);

        $validated = $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'kategori' => ['required', 'in:'.implode(',', Curhat::KATEGORI)],
            'pesan' => ['required', 'string', 'min:10'],
        ]);

        $penerima = User::findOrFail($validated['penerima_id']);

        // Server-side: penerima hanya boleh guru BK atau konselor sebaya.
        if (! $penerima->isGuru() && ! $penerima->isKonselorSebaya()) {
            throw ValidationException::withMessages([
                'penerima_id' => 'Penerima tidak valid. Pilih guru BK atau konselor sebaya.',
            ]);
        }

        // Guru BK hanya boleh menerima dari kelas yang diampunya.
        if ($penerima->isGuru() && ! $penerima->handlesKelas($siswa->kelas)) {
            throw ValidationException::withMessages([
                'penerima_id' => 'Guru BK tersebut tidak menerima curhat dari kelasmu.',
            ]);
        }

        // Konselor sebaya hanya melayani 2 konselor kelasmu.
        if ($penerima->isKonselorSebaya() && ! $penerima->handlesKelas($siswa->kelas)) {
            throw ValidationException::withMessages([
                'penerima_id' => 'Konselor sebaya tersebut tidak melayani kelasmu.',
            ]);
        }

        $curhat = $siswa->curhats()->create([
            'nama_pengirim' => $siswa->name,
            'penerima_id' => $penerima->id,
            'tujuan' => $penerima->role,
            'nama_penerima' => $penerima->name,
            'kategori' => $validated['kategori'],
            'status' => Curhat::STATUS_TERKIRIM,
        ]);

        $curhat->balasans()->create([
            'pengirim_id' => $siswa->id,
            'pengirim_role' => $siswa->role,
            'isi' => $validated['pesan'],
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Curhatmu terkirim.');
    }

    /**
     * Detail percakapan milik siswa.
     */
    public function show(Curhat $curhat)
    {
        $siswa = Auth::user();
        abort_unless($siswa->isSiswa(), 403);

        if ($curhat->isDitangani()) {
            return redirect()->route('siswa.dashboard')->with('error', 'Riwayat percakapan ini sudah ditutup.');
        }

        abort_unless($curhat->penerima_id === $siswa->id || $curhat->siswa_id === $siswa->id, 403);

        if ($curhat->penerima_id === $siswa->id) {
            $curhat->setViewed();
        }

        return view('dashboard.show', compact('curhat'));
    }

    public function reply(Curhat $curhat, Request $request)
    {
        $siswa = Auth::user();

        $isBagianPercakapan = $curhat->siswa_id === $siswa->id || $curhat->penerima_id === $siswa->id;

        abort_unless($siswa->isSiswa() && $isBagianPercakapan, 403);
        abort_if($curhat->isDitangani(), 403);

        $validated = $request->validate([
            'isi' => ['required', 'string', 'min:3'],
        ]);

        $balasan = $curhat->balasans()->create([
            'pengirim_id' => $siswa->id,
            'pengirim_role' => $siswa->role,
            'isi' => $validated['isi'],
        ]);

        if ($curhat->penerima_id === $siswa->id) {
            $curhat->setReplied();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'balasan' => [
                    'id' => $balasan->id,
                    'isi' => $balasan->isi,
                    'pengirim_name' => $siswa->name,
                    'pengirim_role' => $siswa->role,
                    'pengirim_role_label' => $siswa->roleLabel(),
                    'created_at' => $balasan->created_at->translatedFormat('d M Y, H:i'),
                    'created_time' => $balasan->created_at->translatedFormat('H:i'),
                    'is_me' => true,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }
}
