@extends('layouts.app')

@section('title', 'Dashboard Guru BK - Sahabat Aman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Dashboard Guru BK</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Ikhtisar curhat siswa yang masuk ke meja konseling Anda.</p>
        </div>

        {{-- Statistik --}}
        <section
            class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting overflow-hidden">
            <div
                class="p-4 border-b border-outline-variant/50 [&:nth-last-child(-n+2)]:border-b-0 md:[&:nth-last-child(-n+3)]:border-b-0 xl:border-b-0">
                <p class="text-label-sm text-on-surface-variant">Curhat Masuk</p>
                <p class="text-headline-md font-bold text-on-surface">{{ $statistik['masuk'] }}</p>
            </div>
            <div
                class="p-4 border-b border-outline-variant/50 [&:nth-last-child(-n+2)]:border-b-0 md:[&:nth-last-child(-n+3)]:border-b-0 xl:border-b-0">
                <p class="text-label-sm text-on-surface-variant">Belum Dibalas</p>
                <p class="text-headline-md font-bold text-error">{{ $statistik['belumDibalas'] }}</p>
            </div>
            <div
                class="p-4 border-b border-outline-variant/50 [&:nth-last-child(-n+2)]:border-b-0 md:[&:nth-last-child(-n+3)]:border-b-0 xl:border-b-0">
                <p class="text-label-sm text-on-surface-variant">Dilihat</p>
                <p class="text-headline-md font-bold text-on-surface">{{ $statistik['dilihat'] }}</p>
            </div>
            <div
                class="p-4 border-b border-outline-variant/50 [&:nth-last-child(-n+2)]:border-b-0 md:[&:nth-last-child(-n+3)]:border-b-0 xl:border-b-0">
                <p class="text-label-sm text-on-surface-variant">Dibalas</p>
                <p class="text-headline-md font-bold text-tertiary">{{ $statistik['dibalas'] }}</p>
            </div>
            <div
                class="p-4 border-b border-outline-variant/50 [&:nth-last-child(-n+2)]:border-b-0 md:[&:nth-last-child(-n+3)]:border-b-0 xl:border-b-0">
                <p class="text-label-sm text-on-surface-variant">Ditangani</p>
                <p class="text-headline-md font-bold text-primary">{{ $statistik['ditangani'] }}</p>
            </div>
        </section>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('guru.curhat.index') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-primary text-on-primary text-label-lg font-semibold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-lg">chat_bubble</span>
                Buka Kotak Masuk Curhat
            </a>
            <a href="{{ route('guru.rekap.index') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-semibold hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">assessment</span>
                Lihat Rekapitulasi
            </a>
        </div>

        {{-- Curhat terbaru --}}
        <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
            <h2 class="text-title-md font-bold text-on-surface mb-4">Curhat Terbaru</h2>
            @forelse ($terbaru as $curhat)
                <div class="flex flex-wrap items-center justify-between gap-3 py-3 border-b border-outline-variant/40 last:border-0">
                    <div class="min-w-0">
                        <p class="text-label-lg font-bold text-on-surface truncate">
                            {{ $curhat->siswa->name ?? 'Siswa' }}
                            <span class="text-label-sm font-medium text-on-surface-variant">· {{ $curhat->created_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-body-sm text-on-surface-variant truncate">"{{ Str::limit($curhat->pesan, 120) }}"</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md text-label-sm font-semibold border border-emerald-200/50">
                            {{ ucfirst($curhat->kategori) }}
                        </span>
                        <span
                            class="px-2.5 py-1 rounded-md text-label-sm font-bold {{ $curhat->status === 'terkirim' ? 'bg-error-container text-on-error-container' : ($curhat->status === 'ditangani' ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-secondary-fixed text-on-secondary-fixed') }}">
                            {{ \App\Models\Curhat::statusLabel($curhat->status) }}
                        </span>
                        <a href="{{ route('guru.curhat.show', $curhat) }}"
                            class="inline-flex items-center gap-1.5 bg-surface-container-high text-on-surface text-label-sm font-semibold px-3 py-1.5 rounded-xl shadow-xs hover:bg-surface-variant transition-all">
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                            Buka
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <span class="material-symbols-outlined text-outline text-4xl mb-2">inbox</span>
                    <p class="text-body-md text-on-surface-variant">Belum ada curhat masuk.</p>
                </div>
            @endforelse
        </section>

    </div>
@endsection
