@extends('layouts.admin')

@section('title', 'Profil Saya - Admin')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Profil Saya</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Kelola informasi akun dan kata sandi.</p>
        </div>

        <section
            class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant custom-shadow-resting flex flex-col gap-4 relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary-fixed/20 rounded-full blur-3xl pointer-events-none">
            </div>

            <form method="POST" action="{{ route('admin.account.update') }}" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="name" class="text-label-lg font-bold text-on-surface mb-2 block">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl px-4 py-3 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        @error('name')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            required
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl px-4 py-3 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        @error('email')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-outline-variant/40 pt-4 flex flex-col gap-4">
                    <p class="text-label-md text-on-surface-variant">Ganti Kata Sandi <span class="text-outline">(Kosongkan
                            jika
                            tidak diubah)</span></p>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">Password
                                Baru</label>
                            <input type="password" name="password" id="password" minlength="8"
                                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl px-4 py-3 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="text-label-lg font-bold text-on-surface mb-2 block">Konfirmasi
                                Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl px-4 py-3 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-7 py-3.5 rounded-2xl bg-primary text-white text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
                </button>
            </div>
        </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-5 py-2.5 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-7 py-3 rounded-2xl bg-primary text-white text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
                    </button>
                </div>
            </form>

            <div>
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-outline-variant/40 pb-5">
                    <div class="flex items-center gap-3">
                        <div>
                            <h3 class="text-headline-sm font-bold text-on-surface">Keluar Akun</h3>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="logoutBtn" type="submit"
                        class="flex items-center gap-3 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-xl px-4 py-2.5 font-normal transition-colors duration-150 w-full"
                        title="Keluar">
                        <span class="material-symbols-outlined text-lg shrink-0">logout</span>
                        <span class="sidebar-label text-label-md">Keluar</span>
                    </button>
                </form>
            </div>
        </section>
    </div>
@endsection
