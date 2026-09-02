@extends('layouts.admin')

@section('title', 'Edit Siswa - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-xl">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8A7B63]">Panel Admin</p>
        <h1 class="mt-1 text-3xl font-black text-[#214D41]">Edit Siswa</h1>
        <p class="mt-1 text-sm text-[#587067]">Perbarui data siswa berikut.</p>
    </div>

    <div class="mt-6 rounded-[28px] bg-white p-7 shadow-sm ring-1 ring-[#E5DECF]">
        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-[#355C4F]">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name', $siswa->name) }}" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                @error('name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="identifier" class="mb-2 block text-sm font-semibold text-[#355C4F]">NIS / Identifier</label>
                <input id="identifier" name="identifier" type="text" value="{{ old('identifier', $siswa->identifier) }}" class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                @error('identifier') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-[#355C4F]">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $siswa->email) }}" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                @error('email') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-[#355C4F]">Password Baru <span class="font-normal text-[#8A7B63]">(opsional)</span></label>
                <input id="password" name="password" type="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                @error('password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white hover:bg-[#486A5E]">Simpan</button>
                <a href="{{ route('admin.siswa') }}" class="rounded-full border border-[#7C9885] px-5 py-3 text-sm font-semibold text-[#385C4F]">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
