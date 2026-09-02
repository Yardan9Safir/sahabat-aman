@extends('layouts.admin')

@section('title', 'Detail Curhat - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8A7B63]">Panel Admin</p>
            <h1 class="mt-1 text-3xl font-black text-[#214D41]">Detail Curhat</h1>
        </div>
        <a href="{{ route('admin.index') }}" class="rounded-full border border-[#B8C7BD] px-4 py-2 text-sm font-semibold text-[#355C4F]">Kembali</a>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8A7B63]">Curhat #{{ $curhat->id }}</p>
                <h2 class="mt-1 text-2xl font-black text-[#214D41]">{{ ucfirst($curhat->kategori) }}</h2>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#8A7B63]">Pengirim</p>
                <p class="mt-1 font-bold text-[#214D41]">{{ $curhat->siswa->name ?? 'Tanpa pengirim' }}</p>
                <p class="text-xs text-[#8A7B63]">{{ $curhat->siswa ? ucfirst($curhat->siswa->role) : '' }}</p>
            </div>
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#8A7B63]">Penerima</p>
                <p class="mt-1 font-bold text-[#214D41]">{{ $curhat->penerima->name ?? 'Tanpa penerima' }}</p>
                <p class="text-xs text-[#8A7B63]">{{ $curhat->penerima ? ucfirst($curhat->penerima->role) : '' }}</p>
            </div>
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#8A7B63]">Waktu</p>
                <p class="mt-1 font-bold text-[#214D41]">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="mt-4 rounded-2xl bg-[#FBF9F5] p-4 ring-1 ring-[#E5DECF]">
            <p class="text-[11px] uppercase tracking-[0.2em] text-[#8A7B63]">Catatan</p>
            <p class="mt-2 rounded-xl border border-dashed border-[#C8D5CD] bg-[#F3EFE6] px-4 py-3 text-sm text-[#587067]">Isi pesan tidak ditampilkan untuk menjaga kerahasiaan.</p>
        </div>
    </div>
</div>
@endsection