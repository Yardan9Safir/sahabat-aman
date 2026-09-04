@extends('layouts.admin')

@section('title', 'Edit Siswa - RuangTeduh BK')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-1.5 text-label-md text-primary hover:underline font-semibold">
        <span class="material-symbols-outlined text-base">arrow_back</span> Kembali ke Daftar Siswa
    </a>

    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary font-bold text-lg">
                {{ strtoupper(substr($siswa->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-headline-sm font-bold text-on-surface">Edit Data Siswa</h1>
                <p class="text-body-sm text-on-surface-variant">{{ $siswa->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="text-label-lg font-bold text-on-surface mb-2 block">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $siswa->name) }}" required
                    class="w-full bg-surface-container-lowest border-1.5 border-[#E1E8E4] rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('name')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="identifier" class="text-label-lg font-bold text-on-surface mb-2 block">NISN / ID Siswa</label>
                <input type="text" name="identifier" id="identifier" value="{{ old('identifier', $siswa->identifier) }}"
                    class="w-full bg-surface-container-lowest border-1.5 border-[#E1E8E4] rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('identifier')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $siswa->email) }}" required
                    class="w-full bg-surface-container-lowest border-1.5 border-[#E1E8E4] rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('email')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">Password Baru <span class="text-outline font-normal">(Kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" id="password"
                    class="w-full bg-surface-container-lowest border-1.5 border-[#E1E8E4] rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('password')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('admin.siswa') }}" class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">Batal</a>
                <button type="submit" class="px-7 py-3.5 rounded-2xl bg-primary-container text-white text-label-lg font-bold shadow-md hover:bg-primary hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
