@extends('layouts.app')

@section('title', 'Masuk - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-md bg-surface-container-lowest p-7 rounded-3xl soft-shadow border border-surface-variant/40">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-2xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
            <span class="material-symbols-outlined">spa</span>
        </div>
        <div>
            <h1 class="text-headline-sm font-bold text-primary">Sahabat Aman</h1>
            <p class="text-label-sm text-outline">Ruang Aman Curhat Siswa</p>
        </div>
    </div>

    <h2 class="text-headline-sm font-bold text-on-surface mt-6">Masuk</h2>
    <p class="mt-2 text-body-md text-on-surface-variant">Akses dashboard dan riwayat curhatmu.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                class="w-full bg-surface-container-lowest border-1.5 border-[#E1E8E4] rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                placeholder="contoh@email.com">
            @error('email')
                <p class="mt-2 text-xs text-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">Password</label>
            <input id="password" name="password" type="password" required
                class="w-full bg-surface-container-lowest border-1.5 border-[#E1E8E4] rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
        </div>

        <button type="submit" class="w-full rounded-2xl bg-primary-container px-5 py-3.5 text-label-lg font-bold text-on-primary shadow-md hover:bg-primary hover:-translate-y-0.5 transition-all">Masuk ke Akun</button>
    </form>

    <p class="mt-5 text-center text-body-sm text-on-surface-variant">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline">Daftar</a>
    </p>
</div>
@endsection
