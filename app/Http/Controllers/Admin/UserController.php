<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Curhat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Daftar seluruh pengguna (baca, edit, dan hapus).
     */
    public function index(Request $request)
    {
        return view('admin.users', [
            'dataTable' => app(UserDataTable::class)->html(),
            'q' => trim((string) $request->query('q')),
            'role' => in_array($request->query('role'), User::ROLES, true) ? $request->query('role') : '',
        ]);
    }

    /**
     * Data JSON untuk tabel pengguna (DataTables server-side).
     */
    public function dataTable()
    {
        return app(UserDataTable::class)->make()->toJson();
    }

    /**
     * Form tambah akun (siswa, guru, atau konselor sebaya).
     */
    public function create()
    {
        return view('admin.user-edit', ['user' => new User()]);
    }

    /**
     * Simpan akun baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validateAccount($request, null);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'identifier' => $validated['identifier'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'kelas' => $validated['role'] === User::ROLE_SISWA ? $validated['kelas'] : null,
            'guru_kelas' => $validated['role'] === User::ROLE_GURU
                ? array_values($validated['guru_kelas'] ?? [])
                : [],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    /**
     * Form edit identitas pengguna (semua peran).
     */
    public function edit(User $user)
    {
        return view('admin.user-edit', compact('user'));
    }

    /**
     * Simpan perubahan identitas pengguna (nama, email, identifier, peran,
     * kelas siswa, kelas diampu guru, serta password opsional).
     */
    public function update(Request $request, User $user)
    {
        $validated = $this->validateAccount($request, $user);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->identifier = $validated['identifier'];
        $user->role = $validated['role'];
        $user->kelas = $validated['role'] === User::ROLE_SISWA ? $validated['kelas'] : null;
        $user->guru_kelas = $validated['role'] === User::ROLE_GURU
            ? array_values($validated['guru_kelas'] ?? [])
            : [];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Validasi bersama create/update. Akun baru hanya untuk siswa, guru,
     * dan konselor sebaya; password wajib saat create, opsional saat update.
     */
    private function validateAccount(Request $request, ?User $user): array
    {
        $roles = $user ? User::ROLES : [User::ROLE_SISWA, User::ROLE_GURU, User::ROLE_KONSELOR_SEBAYA];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email' . ($user ? ',' . $user->id : '')],
            'identifier' => ['required', 'string', 'max:100', 'unique:users,identifier' . ($user ? ',' . $user->id : '')],
            'role' => ['required', 'in:' . implode(',', $roles)],
            'kelas' => [
                'nullable',
                'required_if:role,' . User::ROLE_SISWA,
                'in:' . implode(',', User::ALL_KELAS)
            ],
            'guru_kelas' => ['array'],
            'guru_kelas.*' => ['in:' . implode(',', User::ALL_KELAS)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);

        // Konselor sebaya: maksimal 10 akun (KonselorA–KonselorJ).
        if ($validated['role'] === User::ROLE_KONSELOR_SEBAYA
            && ($pesan = User::validasiKonselor($user?->id))) {
            throw ValidationException::withMessages([
                'role' => $pesan,
            ]);
        }

        // Kelas guru: tidak boleh double diampu dua guru.
        if ($validated['role'] === User::ROLE_GURU
            && ($pesan = User::validasiGuruKelas($validated['guru_kelas'] ?? [], $user?->id))) {
            throw ValidationException::withMessages([
                'guru_kelas' => $pesan,
            ]);
        }

        return $validated;
    }

    /**
     * Hapus pengguna yang tidak punya data curhat/quotes.
     */
    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403);

        if (
            $user->curhats()->exists()
            || Curhat::where('penerima_id', $user->id)->exists()
            || $user->balasans()->exists()
            || $user->quotes()->exists()
        ) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna masih memiliki data curhat atau quotes dan tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
