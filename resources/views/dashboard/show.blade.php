@extends('layouts.app')

@section('title', 'Detail Curhat Masuk')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">Curhat masuk</p>
                <h1 class="mt-2 text-3xl font-black text-[#214D41]">{{ $curhat->anonim ? 'Siswa anonim' : $curhat->siswa->name }}</h1>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
        </div>

        <div class="mt-6 rounded-2xl bg-[#F9F6F2] p-5 ring-1 ring-[#E5DECF]">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#6D7E79]">Isi curhat</p>
            <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#3C504B]">{{ $curhat->pesan }}</p>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Kategori</p>
                <p class="mt-2 font-bold text-[#214D41]">{{ ucfirst($curhat->kategori) }}</p>
            </div>
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Tujuan</p>
                <p class="mt-2 font-bold text-[#214D41]">{{ $curhat->tujuan === 'teman' ? 'Teman' : 'Konselor/BK' }}</p>
            </div>
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Waktu</p>
                <p class="mt-2 font-bold text-[#214D41]">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <h2 class="text-xl font-bold text-[#214D41]">Balas curhat</h2>
        <form method="POST" action="{{ route('dashboard.reply', $curhat) }}" class="mt-5 space-y-4">
            @csrf
            <textarea name="isi" rows="5" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none" placeholder="Tulis balasan yang empati dan membantu..."></textarea>
            <button type="submit" class="rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white">Kirim balasan</button>
        </form>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <h2 class="text-xl font-bold text-[#214D41]">Riwayat balasan</h2>
        <div class="mt-5 space-y-4">
            @forelse($curhat->balasans as $balasan)
                <div class="rounded-2xl bg-[#F7F4F0] p-4">
                    <p class="font-bold text-[#214D41]">{{ $balasan->pengirim_role === 'teman' ? 'Teman Sebaya' : 'Konselor/BK' }}</p>
                    <p class="mt-2 text-sm leading-7 text-[#3C504B]">{{ $balasan->isi }}</p>
                </div>
            @empty
                <p class="text-sm text-[#587067]">Belum ada balasan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
