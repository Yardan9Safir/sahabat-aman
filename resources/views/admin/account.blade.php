@extends('layouts.admin')

@section('title', 'Pengaturan Akun - BK')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-headline-lg font-bold text-on-surface">Pengaturan Akun</h1>
        <p class="text-body-md text-on-surface-variant mt-1">Kelola informasi akun dan kata sandi Anda.</p>
    </div>

    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">

        <form method="POST" action="{{ route('admin.account.update') }}" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="text-label-lg font-bold text-on-surface mb-2 block">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('name')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('email')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-outline-variant/40 pt-5">
                <p class="text-label-md text-on-surface-variant mb-4">Ubah Kata Sandi <span class="text-outline">(Kosongkan jika tidak diubah)</span></p>

                <div class="flex flex-col gap-5">
                    <div>
                        <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" minlength="8"
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-label-lg font-bold text-on-surface mb-2 block">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none" placeholder="Ulangi kata sandi baru">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('admin.index') }}" class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">Batal</a>
                <button type="submit" class="px-7 py-3.5 rounded-2xl bg-primary text-white text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
