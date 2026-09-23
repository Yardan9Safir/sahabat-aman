@extends('layouts.app')

@section('title', 'Kotak Masuk - Sahabat Aman')

@section('content')

    @if ($curhats->isEmpty())
        <div class="bg-surface-container-lowest p-10 rounded-3xl soft-shadow border border-surface-variant/40 text-center">
            <div
                class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center text-primary mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">chat_bubble</span>
            </div>
            <h2 class="text-headline-sm font-bold text-on-surface">Belum ada percakapan</h2>
            <p class="text-body-md text-on-surface-variant mt-2">Ketika kamu atau orang lain membalas curhatmu, percakapan
                akan muncul di sini.</p>
            @if(Auth::user()->role === 'siswa')
            <a href="{{ route('curhat.create') }}"
                class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-white font-label-lg font-bold shadow-md hover:bg-primary-container transition-all">
                <span class="material-symbols-outlined text-lg">edit_note</span> Mulai Curhat
            </a>
            @endif
        </div>
    @else
        <div
            class="bg-surface-container-lowest p-4 rounded-3xl soft-shadow border border-surface-variant/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-title-md font-bold text-on-surface">Dialog Siswa</h3>
                <p class="text-body-sm text-on-surface-variant">{{ $curhats->count() }} Percakapan</p>
            </div>
            <div class="flex items-center gap-1.5 overflow-x-auto custom-scroll pb-1">
                <a href="{{ route('dashboard') }}"
                    class="px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                    Semua ({{ $curhats->count() }})
                </a>
                <a href="{{ route('dashboard', ['status' => 'terkirim']) }}"
                    class="px-3 py-1.5 rounded-lg {{ request('status') === 'terkirim' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                    Baru
                </a>
                <a href="{{ route('dashboard', ['status' => 'dibalas']) }}"
                    class="px-3 py-1.5 rounded-lg {{ request('status') === 'dibalas' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                    Dibalas
                </a>
                <a href="{{ route('dashboard', ['status' => 'ditangani']) }}"
                    class="px-3 py-1.5 rounded-lg {{ request('status') === 'ditangani' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                    Selesai
                </a>
            </div>
        </div>

        <div class="space-y-3">
            @foreach ($curhats as $curhat)
                <a href="{{ route('dashboard.show', $curhat) }}"
                    class="block p-4 rounded-2xl {{ $curhat->status === 'terkirim' ? 'bg-surface-container-low/90 border-2 border-primary/40 shadow-sm' : 'bg-surface-container-lowest hover:bg-surface-container-low border border-outline-variant/30' }} cursor-pointer transition-all hover:shadow-md">
                    <div class="flex items-start justify-between gap-2 mb-1.5">
                        <div class="flex items-center gap-1.5">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-secondary-container text-on-secondary-container' : 'bg-tertiary-container text-on-tertiary-container' }} text-label-sm">
                                <span
                                    class="material-symbols-outlined text-xs">{{ $curhat->tujuanIcon }}</span>
                                {{ $curhat->tujuanLabel }}
                            </span>
                            @if ($curhat->status === 'dibalas')
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-container text-on-primary-container text-label-sm font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-ping"></span>
                                    Dibalas
                                </span>
                            @elseif($curhat->status === 'terkirim')
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-tertiary-container text-on-tertiary-container text-label-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary-container"></span>
                                    Menunggu Balasan
                                </span>
                            @elseif($curhat->status === 'ditangani')
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant text-label-sm">
                                    <span class="material-symbols-outlined text-xs text-primary">check_circle</span>
                                    Selesai
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-outline text-label-sm">
                                    {{ ucfirst($curhat->status) }}
                                </span>
                            @endif
                        </div>
                        <span class="text-label-sm text-outline">{{ $curhat->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="pl-0">
                        <h3 class="text-title-md text-on-surface text-[15px] leading-snug line-clamp-1 mb-0.5 font-bold">
                            {{ ucfirst($curhat->kategori) }}
                        </h3>
                        <p class="text-label-sm text-primary font-semibold mb-1">
                            {{ $curhat->nama_penerima ?? ($curhat->penerima->name ?? 'Tidak diketahui') }}
                        </p>
                        <p class="text-body-sm text-on-surface-variant line-clamp-2 leading-relaxed">
                            {{ Str::limit($curhat->pesan, 160) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
