@extends('layouts.admin')

@section('title', 'Data Siswa - RuangTeduh BK')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-1">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-headline-lg text-on-surface">Data Siswa</h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-primary border border-primary-fixed-dim text-label-sm">
                    <span class="material-symbols-outlined text-sm">lock</span>
                    Kerahasiaan Terjaga
                </span>
            </div>
            <p class="text-body-md text-on-surface-variant mt-1.5 max-w-3xl leading-relaxed">
                Daftar siswa yang terdaftar di sistem bimbingan konseling.
            </p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 custom-shadow-resting flex items-center justify-between">
            <div>
                <p class="text-label-md text-on-surface-variant">Total Siswa Terdata</p>
                <h2 class="text-headline-md text-on-surface mt-1">{{ $siswas->count() }} Siswa</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-surface-container-low flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">groups</span>
            </div>
        </div>
        <div class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 custom-shadow-resting flex items-center justify-between">
            <div>
                <p class="text-label-md text-on-surface-variant">Aktif di Sistem</p>
                <h2 class="text-headline-md text-primary mt-1">{{ $siswas->filter(fn($s) => $s->curhats->count() > 0)->count() }} Siswa</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary-fixed/40 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-2xl">person</span>
            </div>
        </div>
    </div>

    {{-- Student List --}}
    <section class="bg-surface-container-lowest rounded-3xl border border-outline-variant/50 p-5 custom-shadow-resting">
        <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3 mb-4">
            <h2 class="text-title-md text-on-surface">Daftar Siswa</h2>
            <span class="text-label-sm text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-full">{{ $siswas->count() }} Siswa</span>
        </div>

        <div class="space-y-3 custom-scrollbar max-h-[720px] overflow-y-auto pr-1">
            @forelse($siswas as $siswa)
            <article class="p-4 rounded-2xl bg-surface-container-lowest hover:bg-surface-container-low border border-outline-variant/40 transition-all">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl {{ $siswa->curhats->count() > 0 ? 'bg-primary-container text-on-primary' : 'bg-surface-container-high text-on-surface-variant' }} font-bold flex items-center justify-center text-label-lg">
                            {{ strtoupper(substr($siswa->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-title-md text-on-surface">{{ $siswa->name }}</h3>
                            <p class="text-body-sm text-on-surface-variant mt-0.5">{{ $siswa->identifier ?? '-' }} &bull; {{ ucfirst($siswa->role) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.siswa.edit', $siswa) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </a>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-outline-variant/30 flex flex-wrap items-center gap-2 text-label-sm">
                    @if($siswa->curhats->count() > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#E5F6EB] text-[#21633A] font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#21633A]"></span>
                        {{ $siswa->curhats->count() }} Curhat
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-medium">
                        Belum ada curhat
                    </span>
                    @endif
                </div>
            </article>
            @empty
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-outline text-4xl mb-3">person_search</span>
                <p class="text-headline-sm font-bold text-on-surface">Tidak ada siswa ditemukan</p>
            </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
