@extends('layouts.admin')

@section('title', 'Dashboard Admin - Sahabat Aman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Dashboard Admin</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Ikhtisar Sistem Sahabat Aman: pengguna, curhat, dan quotes.</p>
        </div>

        {{-- Statistik pengguna --}}
        <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
            <h2 class="text-title-md font-bold text-on-surface mb-4">Pengguna</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Total Pengguna</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['pengguna'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Siswa</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['siswa'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Guru BK</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['guru'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Konselor Sebaya</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['konselor'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Admin</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['admin'] }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="mt-4 inline-flex items-center gap-1.5 text-label-md font-semibold text-primary hover:underline">
                <span class="material-symbols-outlined text-base">group</span>
                Kelola Pengguna
            </a>
        </section>

        {{-- Statistik curhat & quotes --}}
        {{-- <section class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/60 custom-shadow-resting">
                <p class="text-label-sm text-on-surface-variant">Total Curhat</p>
                <p class="text-headline-md font-bold text-on-surface">{{ $statistik['curhat'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/60 custom-shadow-resting">
                <p class="text-label-sm text-on-surface-variant">Curhat Belum Dibalas</p>
                <p class="text-headline-md font-bold text-error">{{ $statistik['curhatBaru'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/60 custom-shadow-resting">
                <p class="text-label-sm text-on-surface-variant">Curhat Ditangani</p>
                <p class="text-headline-md font-bold text-primary">{{ $statistik['curhatDitangani'] }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-surface-container-lowest border border-outline-variant/60 custom-shadow-resting">
                <p class="text-label-sm text-on-surface-variant">Total Quotes</p>
                <p class="text-headline-md font-bold text-on-surface">{{ $statistik['quotes'] }}</p>
            </div>
        </section> --}}

        {{-- Curhat terbaru --}}
        {{-- <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
            <h2 class="text-title-md font-bold text-on-surface mb-4">Curhat Terbaru</h2>
            @forelse ($curhatTerbaru as $curhat)
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
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <span class="material-symbols-outlined text-outline text-4xl mb-2">inbox</span>
                    <p class="text-body-md text-on-surface-variant">Belum ada curhat masuk.</p>
                </div>
            @endforelse
        </section> --}}

    </div>
@endsection
