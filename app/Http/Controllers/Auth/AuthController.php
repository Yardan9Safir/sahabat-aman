<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Halaman publik (landing page).
     */
    public function landing()
    {
        $quotes = Quote::untukBeranda();

        return view('home', compact('quotes'));
    }

    public function showLogin()
    {
        $loginQuotes = Quote::untukLogin();

        return view('auth.login', compact('loginQuotes'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route($this->homeRoute(Auth::user()));
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'identifier' => ['required', 'string', 'max:100', 'unique:users,identifier'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:'.implode(',', User::ROLES)],
            'kelas' => ['nullable',
                'required_if:role,'.User::ROLE_SISWA,
                'in:'.implode(',', User::ALL_KELAS)],
        ]);

        // Konselor sebaya: maksimal 10 akun (KonselorA–KonselorJ).
        if ($validated['role'] === User::ROLE_KONSELOR_SEBAYA
            && ($pesan = User::validasiKonselor())) {
            throw ValidationException::withMessages([
                'role' => $pesan,
            ]);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'identifier' => $validated['identifier'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'kelas' => $validated['role'] === User::ROLE_SISWA ? ($validated['kelas'] ?? null) : null,
        ]);

        Auth::login($user);

        return redirect()->route($this->homeRoute($user));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Terima kasih sudah menggunakan Sahabat Aman.');
    }

    /**
     * Penerus tunggal /dashboard: arahkan user ke panel sesuai perannya.
     */
    public function dashboard()
    {
        return redirect()->route($this->homeRoute(Auth::user()));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            if (empty($validated['current_password']) || ! Hash::check($validated['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Password lama salah.'],
                ]);
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // AuthenticateSession memakai hash password di session; sinkronkan
        // supaya ganti password tidak ikut mengeluarkan perangkat ini.
        if (! empty($validated['password'])) {
            $request->session()->put('password_hash_'.Auth::getDefaultDriver(), $user->getAuthPassword());
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Keluarkan akun ini dari semua perangkat/sesi lain, perangkat ini tetap masuk.
     */
    public function logoutOtherDevices(Request $request)
    {
        $validated = $request->validate([
            'logout_password' => ['required', 'string'],
        ]);

        if (! Hash::check($validated['logout_password'], $request->user()->password)) {
            throw ValidationException::withMessages([
                'logout_password' => ['Password salah.'],
            ]);
        }

        Auth::logoutOtherDevices($validated['logout_password']);

        return redirect()->route('profile')->with('success', 'Semua perangkat lain berhasil dikeluarkan.');
    }

    /**
     * Nama route dashboard utama untuk tiap role.
     */
    protected function homeRoute(User $user): string
    {
        return match ($user->role) {
            User::ROLE_SISWA => 'siswa.dashboard',
            User::ROLE_KONSELOR_SEBAYA => 'konselor.dashboard',
            User::ROLE_GURU => 'guru.dashboard',
            User::ROLE_ADMIN => 'admin.dashboard',
            default => 'welcome',
        };
    }
}
