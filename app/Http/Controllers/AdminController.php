<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Curhat::query()->with(['siswa', 'penerima']);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $curhats = $query->latest()->get();

        return view('admin.index', compact('curhats'));
    }

    public function show(Curhat $curhat)
    {
        return view('admin.show', compact('curhat'));
    }

    public function siswa()
    {
        $siswas = User::where('role', 'siswa')->orderBy('name')->get();

        return view('admin.siswa', compact('siswas'));
    }

    public function siswaEdit(User $siswa)
    {
        abort_unless($siswa->role === 'siswa', 404);

        return view('admin.siswa-edit', compact('siswa'));
    }

    public function siswaUpdate(Request $request, User $siswa)
    {
        abort_unless($siswa->role === 'siswa', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identifier' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $siswa->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $siswa->name = $validated['name'];
        $siswa->identifier = $validated['identifier'] ?? null;
        $siswa->email = $validated['email'];

        if (!empty($validated['password'])) {
            $siswa->password = Hash::make($validated['password']);
        }

        $siswa->save();

        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function siswaDestroy(User $siswa)
    {
        abort_unless($siswa->role === 'siswa', 404);

        $siswa->delete();

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil dihapus.');
    }
}
