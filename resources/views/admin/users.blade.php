@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Sahabat Aman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Kelola Pengguna</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Cari, ubah data, atau hapus akun pengguna.</p>
        </div>

        {{-- Pencarian --}}
        <form method="GET" action="{{ route('admin.users.index') }}"
            class="flex flex-wrap items-center gap-2 p-4 bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting">
            <div class="flex items-center gap-2 flex-1 min-w-56 bg-surface-container-low border-1.5 border-outline-variant rounded-xl px-3.5 py-2.5 focus-within:border-primary-container transition-colors">
                <span class="material-symbols-outlined text-outline text-xl">search</span>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama, email, atau NIS/NIP..."
                    class="flex-1 bg-transparent text-body-md text-on-surface placeholder:text-outline/70 outline-none">
            </div>
            <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all">
                Cari
            </button>
            <select name="role" onchange="this.form.submit()"
                class="px-4 py-2.5 rounded-xl bg-surface-container-lowest text-on-surface text-label-lg font-semibold border border-outline-variant/60 hover:bg-surface-container-low transition-colors cursor-pointer">
                <option value="">Semua Peran</option>
                @foreach (\App\Models\User::ROLES as $roleOption)
                    <option value="{{ $roleOption }}" @selected($role === $roleOption)>{{ \App\Models\User::make(['role' => $roleOption])->roleLabel() }}</option>
                @endforeach
            </select>
            @if ($q !== '' || $role !== '')
                <a href="{{ route('admin.users.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-surface-container text-on-surface-variant text-label-lg font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
        </form>

        {{-- Tabel pengguna --}}
        <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting overflow-hidden">
            <div class="p-5 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-outline-variant/60">
                            <th class="py-3 pr-4 text-label-md font-bold text-on-surface-variant">Nama</th>
                            <th class="py-3 pr-4 text-label-md font-bold text-on-surface-variant">Email</th>
                            <th class="py-3 pr-4 text-label-md font-bold text-on-surface-variant">Identifier</th>
                            <th class="py-3 pr-4 text-label-md font-bold text-on-surface-variant">Kelas</th>
                            <th class="py-3 pr-4 text-label-md font-bold text-on-surface-variant">Peran</th>
                            <th class="py-3 pr-4 text-label-md font-bold text-on-surface-variant">Terdaftar</th>
                            <th class="py-3 text-label-md font-bold text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengguna as $penggunaItem)
                            <tr class="border-b border-outline-variant/30 last:border-0 hover:bg-surface-container-low/60 transition-colors">
                                <td class="py-3 pr-4">
                                    <span class="text-label-lg font-bold text-on-surface">{{ $penggunaItem->name }}</span>
                                    @if ($penggunaItem->id === auth()->id())
                                        <span class="ml-1.5 text-label-sm font-semibold text-primary">(Anda)</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-body-sm text-on-surface-variant">{{ $penggunaItem->email }}</td>
                                <td class="py-3 pr-4 text-body-sm text-on-surface-variant">{{ $penggunaItem->identifier ?: '—' }}</td>
                                <td class="py-3 pr-4 text-body-sm text-on-surface-variant">{{ $penggunaItem->kelas ?: '—' }}</td>
                                <td class="py-3 pr-4">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-label-sm font-bold {{ $penggunaItem->isAdmin() ? 'bg-primary-fixed text-on-primary-fixed' : ($penggunaItem->isGuru() ? 'bg-secondary-fixed text-on-secondary-fixed' : ($penggunaItem->isKonselorSebaya() ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-surface-container-high text-on-surface-variant')) }}">
                                        {{ $penggunaItem->roleLabel() }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-body-sm text-on-surface-variant">{{ $penggunaItem->created_at->diffForHumans() }}</td>
                                <td class="py-3 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <a href="{{ route('admin.users.edit', $penggunaItem) }}"
                                            class="inline-flex items-center justify-center bg-surface-container text-primary hover:bg-surface-container-high w-9 h-9 rounded-xl transition-colors shadow-xs"
                                            title="Edit Pengguna">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                        @if ($penggunaItem->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $penggunaItem) }}"
                                                data-swal-confirm="Hapus pengguna {{ $penggunaItem->name }}?"
                                                data-swal-text="Akun akan dihapus permanen dari database."
                                                data-swal-icon="warning" data-swal-confirm-text="Ya, Hapus"
                                                data-swal-cancel-text="Batal">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center bg-error-container text-on-error-container hover:bg-error hover:text-on-error w-9 h-9 rounded-xl transition-colors shadow-xs"
                                                    title="Hapus Pengguna">
                                                    <span class="material-symbols-outlined text-base">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center">
                                    <span class="material-symbols-outlined text-outline text-4xl mb-2">person_search</span>
                                    <p class="text-body-md text-on-surface-variant">Tidak ada pengguna yang cocok.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pengguna->hasPages())
                <div class="px-5 py-4 border-t border-outline-variant/40">
                    {{ $pengguna->links() }}
                </div>
            @endif
        </section>

    </div>
@endsection
