<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use App\Models\User;
use Illuminate\Http\Request;

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
}
