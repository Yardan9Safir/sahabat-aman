@extends('layouts.app')

@section('title', 'Kotak Masuk - Sahabat Aman')

@section('content')

    @php
        $total = $jumlahKategori->sum();
        $adaFilter = (bool) $kategori || (bool) $status;
        $isSiswa = Auth::user()->isSiswa();
        $homeRoute = $isSiswa ? 'siswa.dashboard' : 'konselor.dashboard';
        $showRoute = $isSiswa ? 'siswa.curhat.show' : 'konselor.sesi.show';
    @endphp

    @if ($curhats->isEmpty() && !$adaFilter)
        <div class="bg-surface-container-lowest p-10 rounded-3xl soft-shadow border border-surface-variant/40 text-center">
            <div
                class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center text-primary mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">chat_bubble</span>
            </div>
            <h2 class="text-headline-sm font-bold text-on-surface">Belum ada percakapan</h2>
            <p class="text-body-md text-on-surface-variant mt-2">Ketika kamu atau orang lain membalas curhatmu, percakapan
                akan muncul di sini.</p>
            @if (Auth::user()->role === 'siswa')
                <a href="{{ route('siswa.curhat.create') }}"
                    class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-white font-label-lg font-bold shadow-md hover:bg-primary-container transition-all">
                    <span class="material-symbols-outlined text-lg">edit_note</span> Mulai Curhat
                </a>
            @endif
        </div>
    @else
        <div class="space-y-4">
            <div
                class="bg-surface-container-lowest p-3 rounded-3xl soft-shadow border border-surface-variant/40 flex flex-col gap-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-title-md font-bold text-on-surface">Dialog Siswa</h3>
                        <p class="text-body-sm text-on-surface-variant">
                            @if ($adaFilter)
                                Menampilkan {{ $curhats->count() }} dari {{ $total }} percakapan.
                            @else
                                Total Percakapan : {{ $total }}
                            @endif
                        </p>
                    </div>
                    @if ($adaFilter)
                        <a href="{{ route($homeRoute) }}"
                            class="inline-flex items-center gap-1.5 self-start px-2.5 py-1 rounded-lg bg-surface-container text-on-surface-variant text-label-sm font-semibold hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-base leading-none">filter_alt_off</span>
                            Reset filter
                        </a>
                    @endif
                </div>

                {{-- Filter status --}}
                <div class="flex items-center gap-1 overflow-x-auto custom-scroll pb-1">
                    <span class="text-label-sm text-outline font-semibold shrink-0 pr-1">Status Curhat :</span>
                    <a href="{{ route($homeRoute, array_filter(['kategori' => $kategori])) }}"
                        class="px-2.5 py-1 rounded-lg {{ !request('status') ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                        Semua ({{ $jumlahStatus->sum() }})
                    </a>
                    <a href="{{ route($homeRoute, array_filter(['status' => 'terkirim', 'kategori' => $kategori])) }}"
                        class="px-2.5 py-1 rounded-lg {{ request('status') === 'terkirim' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                        Terkirim
                    </a>
                    <a href="{{ route($homeRoute, array_filter(['status' => 'dilihat', 'kategori' => $kategori])) }}"
                        class="px-2.5 py-1 rounded-lg {{ request('status') === 'dilihat' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                        Dilihat
                    </a>
                    <a href="{{ route($homeRoute, array_filter(['status' => 'dibalas', 'kategori' => $kategori])) }}"
                        class="px-2.5 py-1 rounded-lg {{ request('status') === 'dibalas' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                        Dibalas
                    </a>
                    <a href="{{ route($homeRoute, array_filter(['status' => 'ditangani', 'kategori' => $kategori])) }}"
                        class="px-2.5 py-1 rounded-lg {{ request('status') === 'ditangani' ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                        Sudah Ditangani
                    </a>
                </div>

                {{-- Filter kategori --}}
                <div class="flex items-center gap-1 overflow-x-auto custom-scroll pb-1">
                    <span class="text-label-sm text-outline font-semibold shrink-0 pr-1">Kategori Curhat:</span>
                    <a href="{{ route($homeRoute, array_filter(['status' => $status])) }}"
                        class="px-2.5 py-1 rounded-lg {{ !$kategori ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                        Semua ({{ $total }})
                    </a>
                    @foreach (\App\Models\Curhat::KATEGORI as $item)
                        <a href="{{ route($homeRoute, array_filter(['kategori' => $item, 'status' => $status])) }}"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg {{ $kategori === $item ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }} text-label-sm whitespace-nowrap transition-colors">
                            <span
                                class="material-symbols-outlined text-base leading-none">{{ \App\Models\Curhat::KATEGORI_ICON[$item] }}</span>
                            {{ ucfirst($item) }} ({{ $jumlahKategori[$item] ?? 0 }})
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- isi Dashboard --}}
            <div class="space-y-2">
                @forelse ($curhats as $curhat)
                    <a href="{{ route($showRoute, $curhat) }}"
                        class="block p-3 rounded-2xl {{ $curhat->status === 'terkirim' ? 'bg-surface-container-low/90 border-2 border-primary/40 shadow-sm' : 'bg-surface-container-lowest hover:bg-surface-container-low border border-outline-variant/30' }} cursor-pointer transition-all hover:shadow-md">

                        <div class="flex items-center justify-between gap-2 mb-1">
                            <div class="flex flex-col items-start gap-0.5">
                                <span
                                    class="inline-flex items-center text-md font-bold {{ $curhat->tujuan === 'guru' ? 'text-secondary' : 'text-tertiary' }}">
                                    {{ $curhat->tujuanLabel }}
                                </span>
                                <span class="text-md text-outline">
                                    {{ $curhat->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div>
                                @if ($curhat->status === 'terkirim')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-primary-container text-on-primary-container text-label-sm">
                                        Pesan Sudah Terkirim
                                    </span>
                                @elseif ($curhat->status === 'dibalas')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-tertiary-container text-on-tertiary-container text-label-sm font-semibold">
                                        Pesan Sudah Dibalas
                                    </span>
                                @elseif ($curhat->status === 'ditangani')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-primary-container text-on-primary-container text-label-sm">
                                        Pesan Sudah Ditangani oleh {{ $curhat->nama_penerima }}
                                    </span>
                                @elseif($curhat->status === 'dilihat')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-secondary-fixed font-bold text-label-md">
                                        Pesan Sudah Dilihat, Namun Belum Dibalas
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="pl-0 flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-body-sm text-on-surface-variant line-clamp-2 leading-relaxed">
                                    {{ Str::limit($curhat->pesan, 160) }}
                                </p>
                            </div>
                            @php $isKonselor = Auth::user()->isKonselorSebaya(); @endphp
                            @if ($isKonselor)
                                <form method="POST" action="{{ route('konselor.sesi.destroy', $curhat) }}"
                                    data-swal-confirm="Hapus permanen riwayat ini?"
                                    data-swal-text="Data akan dihapus sepenuhnya dari database dan tidak dapat dikembalikan."
                                    data-swal-icon="warning" data-swal-confirm-text="Ya, Hapus"
                                    data-swal-cancel-text="Batal" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center justify-center bg-error-container text-on-error-container hover:bg-error hover:text-on-error w-8 h-8 rounded-xl transition-colors shadow-xs"
                                        title="Hapus Permanen">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </a>
                @empty
                    <div
                        class="bg-surface-container-lowest p-10 rounded-3xl soft-shadow border border-surface-variant/40 text-center">
                        <div
                            class="w-16 h-16 rounded-2xl bg-surface-container flex items-center justify-center text-primary mx-auto mb-4">
                            <span class="material-symbols-outlined text-3xl">search_off</span>
                        </div>
                        <h2 class="text-headline-sm font-bold text-on-surface">Tidak ada percakapan yang cocok</h2>
                        <p class="text-body-md text-on-surface-variant mt-2">Coba pilih topik atau status lain.</p>
                        <a href="{{ route($homeRoute) }}"
                            class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-on-primary font-label-lg font-bold shadow-md hover:bg-primary-container transition-all">
                            <span class="material-symbols-outlined text-lg">filter_alt_off</span> Reset filter
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
@endsection
