@extends('layouts.app')

@section('title', 'Dashboard - Sahabat Aman')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">Dashboard</p>
            <h1 class="mt-2 text-3xl font-black text-[#214D41]">Daftar curhat masuk</h1>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-4 shadow-sm ring-1 ring-[#E5DECF]">
        <form method="GET" class="grid gap-4 md:grid-cols-3">
            <select name="kategori" class="rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm">
                <option value="">Semua kategori</option>
                <option value="pribadi" {{ request('kategori') === 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                <option value="sosial" {{ request('kategori') === 'sosial' ? 'selected' : '' }}>Sosial</option>
                <option value="belajar" {{ request('kategori') === 'belajar' ? 'selected' : '' }}>Belajar</option>
                <option value="karier" {{ request('kategori') === 'karier' ? 'selected' : '' }}>Karier</option>
            </select>
            <select name="status" class="rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm">
                <option value="">Semua status</option>
                <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                <option value="dilihat" {{ request('status') === 'dilihat' ? 'selected' : '' }}>Dilihat</option>
                <option value="dibalas" {{ request('status') === 'dibalas' ? 'selected' : '' }}>Dibalas</option>
                <option value="ditangani" {{ request('status') === 'ditangani' ? 'selected' : '' }}>Ditangani</option>
            </select>
            <button type="submit" class="rounded-2xl bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white">Filter</button>
        </form>
    </div>

    @if($curhats->isEmpty())
        <div class="rounded-[28px] border border-dashed border-[#C8D5CD] bg-[#F9F7F3] p-10 text-center">
            <p class="text-lg font-bold text-[#214D41]">Tidak ada curhat yang cocok.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($curhats as $curhat)
                <div class="rounded-[28px] bg-white p-5 shadow-sm ring-1 ring-[#E5DECF]">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">{{ ucfirst($curhat->kategori) }}</p>
                            <h2 class="mt-2 text-xl font-bold text-[#214D41]">{{ $curhat->anonim ? 'Siswa anonim' : $curhat->siswa->name }}</h2>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-[#4F635F]">{{ Str::limit($curhat->pesan, 180) }}</p>
                    <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('dashboard.show', $curhat) }}" class="inline-flex items-center justify-center rounded-full border border-[#B8C7BD] px-4 py-2 text-sm font-semibold text-[#355C4F]">Lihat detail</a>
                        @if(Auth::user()->role === 'konselor' && $curhat->status !== 'ditangani')
                            <form method="POST" action="{{ route('dashboard.handle', $curhat) }}">
                                @csrf
                                <button type="submit" class="rounded-full bg-[#3A7A57] px-4 py-2 text-sm font-bold text-white">Tandai ditangani</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
