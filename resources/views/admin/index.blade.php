@extends('layouts.admin')

@section('title', 'Ikhtisar Konseling - BK')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Main Content: Curhat List --}}
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

        <section class="lg:col-span-8 flex flex-col gap-4">
            <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 custom-shadow-resting flex flex-col gap-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-title-md font-bold text-on-surface">Kotak Masuk Curhat Siswa</h3>
                        <p class="text-body-sm text-on-surface-variant">Menunggu tanggapan empatik Guru BK</p>
                    </div>
                    @if ($kategori || $status)
                        <a href="{{ route('admin.index') }}"
                            class="inline-flex items-center gap-1.5 self-start px-3 py-1.5 rounded-lg bg-surface-container text-on-surface-variant text-label-sm font-semibold hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-base leading-none">filter_alt_off</span>
                            Reset filter
                        </a>
                    @endif
                </div>

                {{-- Filter status --}}
                <div class="flex flex-wrap items-center gap-1.5 p-1 bg-surface-container-low rounded-xl border border-outline-variant/40 self-start">
                    <span class="text-label-sm text-on-surface-variant font-semibold px-2">Status Curhat:</span>
                    <a href="{{ route('admin.index', array_filter(['kategori' => $kategori])) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ ! $status ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Semua ({{ $jumlahStatus->sum() }})
                    </a>
                    <a href="{{ route('admin.index', array_filter(['status' => 'terkirim', 'kategori' => $kategori])) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ $status === 'terkirim' ? 'font-bold bg-error-container text-on-error-container' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Belum Dibalas ({{ $jumlahStatus['terkirim'] ?? 0 }})
                    </a>
                    <a href="{{ route('admin.index', array_filter(['status' => 'dibalas', 'kategori' => $kategori])) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ $status === 'dibalas' ? 'font-bold bg-secondary-fixed text-on-secondary-fixed' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Dibalas ({{ $jumlahStatus['dibalas'] ?? 0 }})
                    </a>
                    <a href="{{ route('admin.index', array_filter(['status' => 'ditangani', 'kategori' => $kategori])) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ $status === 'ditangani' ? 'font-bold bg-surface-container-high text-primary' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Selesai ({{ $jumlahStatus['ditangani'] ?? 0 }})
                    </a>
                </div>

                {{-- Filter kategori --}}
                <div class="flex flex-wrap items-center gap-1.5 p-1 bg-surface-container-low rounded-xl border border-outline-variant/40 self-start">
                    <span class="text-label-sm text-on-surface-variant font-semibold px-2">Kategori:</span>
                    <a href="{{ route('admin.index', array_filter(['status' => $status])) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ ! $kategori ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                        Semua ({{ $jumlahKategori->sum() }})
                    </a>
                    @foreach (\App\Models\Curhat::KATEGORI as $item)
                        <a href="{{ route('admin.index', array_filter(['kategori' => $item, 'status' => $status])) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ $kategori === $item ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                            {{ ucfirst($item) }} ({{ $jumlahKategori[$item] ?? 0 }})
                        </a>
                    @endforeach
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
                        <span class="bg-surface-container-high text-primary px-2.5 py-1 rounded-full text-label-sm font-semibold">Kategori Masalah : {{ ucfirst($curhat->kategori) }}</span>
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
                        <span>Kondisi: {{ $curhat->status === 'terkirim' ? 'Menunggu Respon' : ($curhat->status === 'dibalas' ? 'Sedang Ditangani' : 'Sudah Ditangani') }}</span>
                    </div>
                    @if ($curhat->status === 'ditangani')
                    <a href="{{ route('admin.show', $curhat) }}" class="flex items-center gap-1.5 bg-surface-container-high text-on-surface font-label-md font-semibold px-4 py-2 rounded-xl shadow-xs hover:bg-surface-variant transition-all">
                        <span class="material-symbols-outlined text-base">history</span>
                        <span>Lihat Riwayat</span>
                    </a>
                    {{-- Tombol Hapus Permanen Riwayat chat oleh guru/konselor sebaya --}}
                    <form method="POST" action="{{ route('dashboard.destroy', $curhat) }}"
                        data-swal-confirm="Hapus permanen riwayat ini?"
                        data-swal-text="Data akan dihapus sepenuhnya dari database dan tidak dapat dikembalikan."
                        data-swal-icon="warning" data-swal-confirm-text="Ya, Hapus" data-swal-cancel-text="Batal">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center bg-error-container text-on-error-container hover:bg-error hover:text-on-error w-9 h-9 rounded-xl transition-colors shadow-xs" title="Hapus Permanen">
                            <span class="material-symbols-outlined text-base">delete</span>
                        </button>
                    </form>
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
