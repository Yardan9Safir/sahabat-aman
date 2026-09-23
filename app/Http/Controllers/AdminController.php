<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Curhat::query()->with(['siswa', 'penerima'])->where('tujuan', 'guru');

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
        abort_unless($curhat->tujuan === 'guru', 403);

        $curhat->load(['siswa', 'penerima', 'balasans.pengirim']);

        if ($curhat->penerima_id === Auth::id()) {
            $curhat->setViewed();
        }

        return view('admin.show', compact('curhat'));
    }

    public function account()
    {
        $user = Auth::user();

        return view('admin.account', compact('user'));
    }

    public function accountUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.account')->with('success', 'Akun berhasil diperbarui.');
    }
}
