@extends('layouts.app')

@section('title', 'Rekapitulasi Curhat - Sahabat Aman')

@section('content')
    @php
        $bulanNama = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        $maksBulan = $rekap['perBulan']->max() ?: 1;
        $maksKategori = $rekap['perKategori']->max() ?: 1;
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Rekapitulasi Curhat</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Ringkasan curhat siswa berdasarkan kategori, status, dan waktu.</p>
        </div>

        {{-- Total --}}
        <section class="p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/60 custom-shadow-resting">
            <p class="text-label-sm text-on-surface-variant">Total Curhat Masuk</p>
            <p class="text-display-sm font-bold text-primary">{{ $rekap['total'] }}</p>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Per kategori --}}
            <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
                <h2 class="text-title-md font-bold text-on-surface mb-4">Per Kategori</h2>
                <div class="flex flex-col gap-3">
                    @forelse ($rekap['perKategori'] as $kategori => $total)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-label-md font-semibold text-on-surface capitalize">{{ $kategori }}</span>
                                <span class="text-label-md font-bold text-on-surface">{{ $total }}</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-surface-container overflow-hidden">
                                <div class="h-full rounded-full bg-primary"
                                    style="width: {{ ($total / $maksKategori) * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-body-sm text-on-surface-variant">Belum ada data.</p>
                    @endforelse
                </div>
            </section>

            {{-- Per status --}}
            <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
                <h2 class="text-title-md font-bold text-on-surface mb-4">Per Status</h2>
                <div class="flex flex-col gap-2.5">
                    @forelse ($rekap['perStatus'] as $status => $total)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low border border-outline-variant/40">
                            <span class="text-label-md font-semibold text-on-surface">{{ \App\Models\Curhat::statusLabel($status) }}</span>
                            <span
                                class="px-2.5 py-1 rounded-md text-label-sm font-bold {{ $status === 'terkirim' ? 'bg-error-container text-on-error-container' : ($status === 'ditangani' ? 'bg-primary-fixed text-on-primary-fixed' : ($status === 'dibalas' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-secondary-fixed text-on-secondary-fixed')) }}">
                                {{ $total }}
                            </span>
                        </div>
                    @empty
                        <p class="text-body-sm text-on-surface-variant">Belum ada data.</p>
                    @endforelse
                </div>
            </section>

        </div>

        {{-- Per bulan --}}
        <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
            <h2 class="text-title-md font-bold text-on-surface mb-4">Per Bulan</h2>
            <div class="flex flex-col gap-3">
                @forelse ($rekap['perBulan'] as $bulan => $total)
                    @php
                        [$tahun, $nomorBulan] = explode('-', $bulan);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-label-md font-semibold text-on-surface">{{ $bulanNama[$nomorBulan] ?? $nomorBulan }} {{ $tahun }}</span>
                            <span class="text-label-md font-bold text-on-surface">{{ $total }}</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-surface-container overflow-hidden">
                            <div class="h-full rounded-full bg-secondary-fixed"
                                style="width: {{ ($total / $maksBulan) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-body-sm text-on-surface-variant">Belum ada data.</p>
                @endforelse
            </div>
        </section>

    </div>
@endsection
