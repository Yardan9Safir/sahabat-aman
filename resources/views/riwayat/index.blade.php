@extends('layouts.app')

@section('title', 'Riwayat Curhat')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">Riwayat</p>
            <h1 class="mt-2 text-3xl font-black text-[#214D41]">Curhatmu</h1>
        </div>
        <a href="{{ route('curhat.create') }}" class="rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white">Buat curhat baru</a>
    </div>

    @if($curhats->isEmpty())
        <div class="rounded-[28px] border border-dashed border-[#C8D5CD] bg-[#F9F7F3] p-10 text-center">
            <div class="text-5xl mb-4">💌</div>
            <h2 class="text-xl font-bold text-[#214D41]">Belum ada curhat</h2>
            <p class="mt-2 text-sm text-[#587067]">Semua cerita yang kamu kirim akan muncul di sini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($curhats as $curhat)
                <a href="{{ route('riwayat.show', $curhat) }}" class="block rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-[#E5DECF] transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">{{ $curhat->kategori->icon ?? '' }} {{ $curhat->kategori->nama ?? 'N/A' }}</p>
                            <h2 class="mt-2 text-xl font-bold text-[#214D41]">Untuk {{ $curhat->tujuan === 'guru' ? 'Guru BK' : 'N/A' }}</h2>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
                    </div>
                    <p class="mt-4 line-clamp-2 text-sm leading-6 text-[#4F635F]">{{ Str::limit($curhat->pesan, 160) }}</p>
                    <div class="mt-4 flex items-center justify-between text-xs text-[#6D7E79]">
                        <span>{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
