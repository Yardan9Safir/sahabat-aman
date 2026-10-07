<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curhat;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Ikhtisar sistem: jumlah pengguna, curhat, dan quotes.
     */
    public function index()
    {
        $penggunaPerRole = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $curhatPerStatus = Curhat::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statistik = [
            'pengguna' => User::count(),
            'siswa' => $penggunaPerRole[User::ROLE_SISWA] ?? 0,
            'guru' => $penggunaPerRole[User::ROLE_GURU] ?? 0,
            'konselor' => $penggunaPerRole[User::ROLE_KONSELOR_SEBAYA] ?? 0,
            'admin' => $penggunaPerRole[User::ROLE_ADMIN] ?? 0,
            'curhat' => Curhat::count(),
            'curhatBaru' => $curhatPerStatus[Curhat::STATUS_TERKIRIM] ?? 0,
            'curhatDitangani' => $curhatPerStatus[Curhat::STATUS_DITANGANI] ?? 0,
            'quotes' => Quote::count(),
        ];

        $curhatTerbaru = Curhat::query()
            ->with('siswa', 'penerima')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('statistik', 'curhatTerbaru'));
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.account')->with('success', 'Akun berhasil diperbarui.');
    }
}
