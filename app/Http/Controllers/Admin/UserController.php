<?php

namespace App\Http\Controllers\Admin;

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
        $q = trim((string) $request->query('q'));
        $role = in_array($request->query('role'), User::ROLES, true) ? $request->query('role') : '';

        $pengguna = User::query()
            ->when($q !== '', fn ($query) => $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('identifier', 'like', "%{$q}%");
            }))
            ->when($role !== '', fn ($query) => $query->where('role', $role))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('pengguna', 'q', 'role'));
        return view('admin.users', compact('pengguna', 'q'));
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'identifier' => ['required', 'string', 'max:100', 'unique:users,identifier,'.$user->id],
            'role' => ['required', 'in:'.implode(',', User::ROLES)],
            'kelas' => ['nullable',
                'required_if:role,'.User::ROLE_SISWA,
                'required_if:role,'.User::ROLE_KONSELOR_SEBAYA,
                'in:'.implode(',', User::ALL_KELAS)],
            'guru_kelas' => ['array'],
            'guru_kelas.*' => ['in:'.implode(',', User::ALL_KELAS)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Setiap kelas hanya memiliki 2 konselor sebaya.
        if ($validated['role'] === User::ROLE_KONSELOR_SEBAYA && ! empty($validated['kelas'])) {
            $konselorLain = User::where('role', User::ROLE_KONSELOR_SEBAYA)
                ->where('kelas', $validated['kelas'])
                ->where('id', '!=', $user->id)
                ->count();

            if ($konselorLain >= 2) {
                throw ValidationException::withMessages([
                    'kelas' => 'Setiap kelas hanya memiliki 2 konselor sebaya.',
                ]);
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->identifier = $validated['identifier'];
        $user->role = $validated['role'];
        $user->kelas = in_array($validated['role'], [User::ROLE_SISWA, User::ROLE_KONSELOR_SEBAYA], true)
            ? $validated['kelas']
            : null;
        $user->guru_kelas = $validated['role'] === User::ROLE_GURU
            ? array_values($validated['guru_kelas'] ?? [])
            : [];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna yang tidak punya data curhat/quotes.
     */
    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403);

        if ($user->curhats()->exists()
            || Curhat::where('penerima_id', $user->id)->exists()
            || $user->balasans()->exists()
            || $user->quotes()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna masih memiliki data curhat atau quotes dan tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
