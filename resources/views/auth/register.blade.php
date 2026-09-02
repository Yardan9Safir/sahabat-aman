@extends('layouts.app')

@section('title', 'Daftar - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-xl rounded-[28px] bg-white p-7 shadow-[0_18px_45px_rgba(69,102,89,0.08)] ring-1 ring-[#E5DECF]">
    <h1 class="text-3xl font-black text-[#214D41]">Buat akun</h1>
    <p class="mt-2 text-sm text-[#587067]">Daftar sebagai siswa atau guru.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-[#355C4F]">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                @error('name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="identifier" class="mb-2 block text-sm font-semibold text-[#355C4F]">NIS / Identifier</label>
                <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                @error('identifier') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-[#355C4F]">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
            @error('email') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-[#355C4F]">Password</label>
                <input id="password" name="password" type="password" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#355C4F]">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
            </div>
        </div>

        <div>
            <label for="role" class="mb-2 block text-sm font-semibold text-[#355C4F]">Peran</label>
            <select id="role" name="role" class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                <option value="siswa">Siswa</option>
                <option value="guru">Guru</option>
            </select>
        </div>

        @error('password')
            <p class="text-xs text-red-600">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white shadow-md hover:bg-[#486A5E]">Daftar</button>
    </form>
</div>
@endsection
