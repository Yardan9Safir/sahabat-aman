@extends('layouts.app')

@section('title', 'Riwayat Curhat - Sahabat Aman')

@section('content')

@if($curhats->isEmpty())
<div class="bg-surface-container-lowest p-10 rounded-3xl soft-shadow border border-surface-variant/40 text-center">
    <div class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center text-primary mx-auto mb-4">
        <span class="material-symbols-outlined text-3xl">bookmark_heart</span>
    </div>
    <h2 class="text-headline-sm font-bold text-on-surface">Belum ada riwayat curhat</h2>
    <p class="text-body-md text-on-surface-variant mt-2">Semua cerita yang kamu kirim akan muncul di sini.</p>
    @if(Auth::user()->role === 'siswa')
    <a href="{{ route('curhat.create') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-white font-label-lg font-bold shadow-md hover:bg-primary-container transition-all">
        <span class="material-symbols-outlined text-lg">edit_note</span> Mulai Curhat
    </a>
    @endif
</div>
@else
<div class="space-y-3">
    @foreach($curhats as $curhat)
    <a href="{{ route('riwayat.show', $curhat) }}" class="block p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 hover-lift cursor-pointer transition-all hover:shadow-md">
        <div class="flex items-start justify-between gap-2 mb-1.5">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-secondary-container text-on-secondary-container' : 'bg-tertiary-container text-on-tertiary-container' }} text-label-sm">
                    <span class="material-symbols-outlined text-xs">{{ $curhat->tujuanIcon }}</span>
                    {{ $curhat->tujuanLabel }}
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->status === 'terkirim' ? 'bg-tertiary-container text-on-tertiary-container' : ($curhat->status === 'dibalas' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container text-on-surface-variant') }} text-label-sm font-semibold">
                    {{ ucfirst($curhat->status) }}
                </span>
            </div>
            <span class="text-label-sm text-outline">{{ $curhat->created_at->diffForHumans() }}</span>
        </div>
        <div>
            <h3 class="text-title-md text-on-surface text-[15px] leading-snug mb-0.5 font-bold">{{ ucfirst($curhat->kategori) }}</h3>
            <p class="text-label-sm text-primary font-semibold mb-1">Penerima: {{ $curhat->nama_penerima ?? $curhat->penerima->name ?? 'Tidak diketahui' }}</p>
            <p class="text-body-sm text-on-surface-variant line-clamp-2 leading-relaxed">{{ Str::limit($curhat->pesan, 160) }}</p>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection
