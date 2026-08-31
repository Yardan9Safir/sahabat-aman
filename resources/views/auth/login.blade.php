@extends('layouts.app')

@section('title', 'Masuk - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-md rounded-[28px] bg-white p-7 shadow-[0_18px_45px_rgba(69,102,89,0.08)] ring-1 ring-[#E5DECF]">
    <h1 class="text-3xl font-black text-[#214D41]">Masuk</h1>
    <p class="mt-2 text-sm text-[#587067]">Akses dashboard dan riwayat curhatmu.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-[#355C4F]">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none" placeholder="contoh@email.com">
            @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-[#355C4F]">Password</label>
            <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none" placeholder="••••••••">
        </div>

        <button type="submit" class="w-full rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white shadow-md hover:bg-[#486A5E]">Masuk ke akun</button>
    </form>

    <p class="mt-5 text-center text-sm text-[#587067]">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-[#375D52]">Daftar</a>
    </p>
</div>
@endsection
