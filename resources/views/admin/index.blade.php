@extends('layouts.admin')

@section('title', 'Ikhtisar Konseling - BK')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Stat Cards --}}
    <section class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-1 gap-4">
        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting custom-shadow-interactive transition-all">
            <div class="flex items-center justify-between">
                <span class="text-label-md text-on-surface-variant font-medium">Curhat Masuk Baru</span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-headline-lg text-on-surface font-bold">{{ $curhats->where('status', 'terkirim')->count() }} Pesan</span>
            </div>
            <p class="text-body-sm text-on-surface-variant mt-1">Memerlukan validasi awal konselor</p>
        </div>

        {{-- <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting custom-shadow-interactive transition-all relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-error-container/20 rounded-bl-full pointer-events-none"></div>
            <div class="flex items-center justify-between">
                <span class="text-label-md text-error font-semibold">Butuh Respon Cepat</span>
                <div class="w-9 h-9 rounded-xl bg-error-container text-on-error-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">priority_high</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-headline-lg text-error font-bold">{{ $curhats->where('status', 'terkirim')->count() }} Siswa</span>
            </div>
            <p class="text-body-sm text-on-surface-variant mt-1">Indikasi perlu perhatian segera</p>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting custom-shadow-interactive transition-all">
            <div class="flex items-center justify-between">
                <span class="text-label-md text-on-surface-variant font-medium">Dalam Pendampingan</span>
                <div class="w-9 h-9 rounded-xl bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">diversity_3</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-headline-lg text-on-surface font-bold">{{ $curhats->where('status', 'dibalas')->count() }} Siswa</span>
                <span class="text-label-sm text-secondary font-semibold">Aktif</span>
            </div>
            <p class="text-body-sm text-on-surface-variant mt-1">Program bimbingan bertahap</p>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting custom-shadow-interactive transition-all">
            <div class="flex items-center justify-between">
                <span class="text-label-md text-on-surface-variant font-medium">Selesai Ditangani</span>
                <div class="w-9 h-9 rounded-xl bg-surface-container-high text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">task_alt</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-headline-lg text-on-surface font-bold">{{ $curhats->where('status', 'ditangani')->count() }} Kasus</span>
            </div>
            <p class="text-body-sm text-on-surface-variant mt-1">Tercapai target perkembangan</p>
        </div> --}}

    </section>

    {{-- Main Content: Curhat List --}}
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

        <section class="lg:col-span-8 flex flex-col gap-4">
            <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 custom-shadow-resting flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-title-md font-bold text-on-surface">Kotak Masuk Curhat Siswa</h3>
                    <p class="text-body-sm text-on-surface-variant">Menunggu tanggapan empatik Guru BK</p>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 p-1 bg-surface-container-low rounded-xl border border-outline-variant/40">
                    <a href="{{ route('admin.index') }}" class="px-3 py-1.5 rounded-lg text-label-md {{ !request('kategori') && !request('status') ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Semua ({{ $curhats->count() }})
                    </a>
                    <a href="{{ route('admin.index', ['status' => 'terkirim']) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ request('status') === 'terkirim' ? 'font-bold bg-error-container text-on-error-container' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Belum Dibalas ({{ $curhats->where('status', 'terkirim')->count() }})
                    </a>
                    <a href="{{ route('admin.index', ['status' => 'dibalas']) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ request('status') === 'dibalas' ? 'font-bold bg-secondary-fixed text-on-secondary-fixed' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Dibalas ({{ $curhats->where('status', 'dibalas')->count() }})
                    </a>
                </div>
            </div>
            @forelse($curhats as $curhat)
            <article class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/80 custom-shadow-resting custom-shadow-interactive transition-all">
                <div class="flex flex-wrap items-start justify-between gap-2 border-b border-outline-variant/40 pb-3">
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-headline-sm text-base font-bold text-on-surface">{{ $curhat->nama_pengirim ?? ($curhat->siswa->name ?? 'Siswa') }}</h4>
                                @if(isset($curhat->siswa->identifier))
                                <span class="bg-surface-container px-2 py-0.5 rounded text-label-sm font-semibold text-on-surface-variant">{{ $curhat->siswa->identifier }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-label-sm text-on-surface-variant mt-0.5">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span>{{ $curhat->created_at->diffForHumans() }}</span>
                                <span>&bull;</span>
                                <span class="text-primary font-medium">Ditujukan: {{ $curhat->tujuan === 'guru' ? 'Guru BK' : 'Peer' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-surface-container-high text-primary px-2.5 py-1 rounded-full text-label-sm font-semibold">{{ ucfirst($curhat->kategori) }}</span>
                        @if($curhat->status === 'terkirim')
                        <span class="bg-error-container text-on-error-container px-2.5 py-1 rounded-full text-label-sm font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-error animate-ping"></span>
                            Status : Belum Dibalas
                        </span>
                        @elseif($curhat->status === 'dibalas')
                        <span class="bg-secondary-fixed text-on-secondary-fixed px-2.5 py-1 rounded-full text-label-sm font-bold">Status : Dibalas</span>
                        @else
                        <span class="bg-primary-fixed text-on-primary-fixed px-2.5 py-1 rounded-full text-label-sm font-bold">Status : Selesai</span>
                        @endif
                    </div>
                </div>

                <div class="mt-3.5 space-y-2">
                    <h5 class="text-title-md font-bold text-on-surface">{{ ucfirst($curhat->kategori) }}</h5>
                    <div class="p-3.5 bg-surface-container-low/70 rounded-xl border border-outline-variant/40">
                        <p class="text-body-md text-on-surface italic leading-relaxed">"{{ Str::limit($curhat->pesan, 200) }}"</p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-outline-variant/40 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-label-sm text-on-surface-variant">
                        <span>Kondisi: {{ $curhat->status === 'terkirim' ? 'Menunggu Respon' : ($curhat->status === 'dibalas' ? 'Sedang Ditangani' : 'Selesai') }}</span>
                    </div>
                    @if ($curhat->status === 'ditangani')
                    <span class="inline-flex items-center gap-1.5 bg-error text-on-error font-label-md font-semibold px-4 py-2 rounded-xl shadow-xs">
                        <span class="material-symbols-outlined text-base">task_alt</span>
                        Sudah Ditangani
                    </span>
                    @else
                    <a href="{{ route('admin.show', $curhat) }}" class="flex items-center gap-1.5 bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary font-label-md font-semibold px-4 py-2 rounded-xl shadow-xs transition-all">
                        <span class="material-symbols-outlined text-base">reply</span>
                        <span>Balas Curhat</span>
                    </a>
                    @endif
                </div>
            </article>
            @empty
            <div class="bg-surface-container-lowest p-10 rounded-3xl border border-dashed border-outline-variant/60 text-center">
                <span class="material-symbols-outlined text-outline text-4xl mb-3">inbox</span>
                <p class="text-headline-sm font-bold text-on-surface">Tidak ada curhat yang cocok</p>
                <p class="text-body-sm text-on-surface-variant mt-1">Semua curhat siswa sudah ditangani.</p>
            </div>
            @endforelse
        </section>

    </div>
    
</div>
@endsection
