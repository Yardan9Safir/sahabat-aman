@extends('layouts.admin')

@section('title', 'Kelola Kelas Guru - Sahabat Aman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant custom-shadow-resting flex flex-col gap-4 relative overflow-hidden">
            {{-- Header --}}
            <div>
                <h1 class="text-headline-lg font-bold text-on-surface tracking-tight">Kelola Kelas Guru BK</h1>
                <p class="text-body-md text-on-surface-variant mt-0.5">
                    Setiap guru BK hanya menerima curhat dari kelas yang diampunya. Satu kelas hanya boleh diampu satu
                    guru.
                </p>
            </div>

            {{-- KPI Metrics Ribbon --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                <div
                    class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/40 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed/40 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[22px]">badge</span>
                    </div>
                    <div>
                        <div class="text-headline-sm text-on-surface">{{ $statistik['guru'] }} Guru</div>
                        <div class="text-label-xs text-on-surface-variant font-medium">Total Guru BK</div>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/40 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-secondary-fixed/40 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-[22px]">meeting_room</span>
                    </div>
                    <div>
                        <div class="text-headline-sm text-on-surface">{{ $statistik['rombel'] }} Kelas</div>
                        <div class="text-label-xs text-on-surface-variant font-medium">Tingkat 7, 8, dan 9</div>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/40 shadow-sm flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-200">
                        <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                    </div>
                    <div>
                        <div class="text-headline-sm text-amber-900">{{ $statistik['kosong'] }} Kelas</div>
                        <div class="text-label-xs text-amber-700 font-medium">Belum Diampu Guru</div>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest rounded-xl p-3.5 border border-outline-variant/40 shadow-sm flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-200">
                        <span class="material-symbols-outlined text-[22px]">verified</span>
                    </div>
                    <div>
                        <div class="text-headline-sm text-emerald-800">{{ $statistik['persen'] }}% Kelas</div>
                        <div class="text-label-xs text-emerald-600 font-medium">Sudah Terdistribusi</div>
                    </div>
                </div>
            </div>

            {{-- Data Gurus --}}
            @if ($gurus->isEmpty())
                <section
                    class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting p-10 text-center">
                    <span class="material-symbols-outlined text-outline text-4xl mb-2">group</span>
                    <p class="text-body-md text-on-surface-variant">Belum ada akun guru BK.</p>
                </section>
            @elseif ($terpilih)
                {{-- Split Workspace: Daftar Guru & Panel Penugasan --}}
                <div class="flex flex-col md:flex-row gap-5 items-start">

                    {{-- LEFT: Daftar Guru --}}
                    <div
                        class="w-full md:w-72 lg:w-96 shrink-0 bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm flex flex-col overflow-hidden">
                        <div class="p-3.5 border-b border-outline-variant/40 flex items-center justify-between">
                            <div class="text-title-md text-on-surface font-semibold flex items-center gap-2">
                                <span>Daftar Guru</span>
                                <span
                                    class="text-label-xs bg-surface-container text-on-surface-variant px-2 py-0.5 rounded-full font-bold">{{ $gurus->count() }}</span>
                            </div>
                            <span class="text-label-xs text-outline">Klik untuk edit</span>
                        </div>

                        <div class="p-3 space-y-2.5 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            @foreach ($gurus as $guru)
                                @php $jumlah = count($guru->assignedKelas()); @endphp
                                <a href="{{ route('admin.kelas.index', ['guru' => $guru->id]) }}"
                                    class="block p-3.5 rounded-xl border transition-all shadow-sm {{ $guru->id === $terpilih->id
                                        ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                        : 'border-outline-variant/40 bg-surface-container-lowest hover:border-outline-variant hover:bg-surface-container' }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            {{-- <div class="w-[38px] h-[38px] rounded-[10px] bg-amber-100 flex items-center justify-center shrink-0 text-amber-700">
                                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">school</span>
                                        </div> --}}
                                            <div class="min-w-0">
                                                <h3
                                                    class="text-title-md text-on-surface font-semibold leading-tight truncate">
                                                    {{ $guru->name }}</h3>
                                                <p class="text-body-sm text-on-surface-variant truncate">
                                                    {{ $guru->email }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="h-7 px-3 rounded-full text-label-sm flex items-center justify-center font-bold shrink-0 {{ $jumlah > 0 ? 'bg-primary-fixed/50 text-on-primary-fixed-variant' : 'bg-amber-100 text-amber-800' }}">
                                            Mengampu {{ $jumlah }} kelas
                                        </span>
                                    </div>
                                    <div
                                        class="mt-3 pt-2.5 border-t border-outline-variant/30 flex items-center justify-between text-label-xs">
                                        @if ($jumlah > 0)
                                            <span class="text-emerald-700 flex items-center gap-1 font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Siap
                                            </span>
                                        @else
                                            <span class="text-amber-700 flex items-center gap-1 font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Perlu Ditugaskan
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- RIGHT: Panel Penugasan --}}
                    <div
                        class="w-full flex-1 bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm flex flex-col overflow-hidden">
                        <form method="POST" action="{{ route('admin.kelas.update', $terpilih) }}">
                            @csrf
                            @method('PUT')

                            {{-- Identity header --}}
                            <div class="p-6 border-b border-outline-variant/40">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h2 class="text-headline-md text-on-surface font-bold">
                                                    {{ $terpilih->name }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <span id="badge-counter"
                                        class="h-[34px] px-4 rounded-full bg-primary-fixed/50 text-on-primary-fixed-variant text-title-md flex items-center justify-center font-bold tracking-tight shadow-sm self-start sm:self-center">
                                        {{ count($terpilih->assignedKelas()) }} kelas diampu
                                    </span>
                                </div>

                                {{-- Legend --}}
                                <div
                                    class="mt-5 pt-4 border-t border-outline-variant/30 flex flex-wrap items-center gap-4 text-label-xs">
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="w-3.5 h-3.5 rounded border-[1.5px] border-primary bg-primary/10"></span>
                                        <span class="text-on-surface font-medium">Diampu Guru Ini (<span
                                                id="legend-aktif">{{ count($terpilih->assignedKelas()) }}</span>)</span>
                                    </div>
                                </div>
                            </div>

                            @error('kelas')
                                <p class="px-6 pt-4 text-body-sm text-error">{{ $message }}</p>
                            @enderror

                            {{-- Matrix --}}
                            <div class="p-6 space-y-6 max-h-[55vh] overflow-y-auto custom-scrollbar">
                                @foreach ([7, 8, 9] as $tingkat)
                                    <div
                                        class="rounded-xl p-4 border border-outline-variant/40 bg-surface-container-lowest">
                                        <div class="flex items-center justify-between mb-3 gap-2 flex-wrap">
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-title-md text-on-surface font-bold">Kelas
                                                    {{ $tingkat }}</h3>
                                                <span data-count-tingkat="{{ $tingkat }}"
                                                    class="text-label-xs px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-semibold">
                                                    0 Kelas Terpilih
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" data-select="{{ $tingkat }}"
                                                    class="text-label-xs font-semibold text-primary hover:text-primary-container px-2 py-1 rounded hover:bg-surface-container transition-colors">
                                                    Pilih Semua
                                                </button>
                                                <span class="text-outline-variant">|</span>
                                                <button type="button" data-reset="{{ $tingkat }}"
                                                    class="text-label-xs font-semibold text-outline hover:text-error px-2 py-1 rounded hover:bg-surface-container transition-colors">
                                                    Reset Pilihan
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-1.5" data-group="{{ $tingkat }}">
                                            @foreach ($semuaKelas as $kelas)
                                                @if (!str_starts_with($kelas, (string) $tingkat))
                                                    @continue
                                                @endif
                                                @php
                                                    $diambil = in_array($kelas, $terpilih->assignedKelas(), true);
                                                    $lain = $pemilik[$kelas] ?? null;
                                                @endphp
                                                <label
                                                    class="kelas-chip h-[34px] min-w-[42px] px-2.5 rounded-lg border text-[13px] flex items-center justify-center gap-1.5 transition-all select-none {{ $lain
                                                        ? 'border-outline-variant/40 bg-surface-container text-outline cursor-not-allowed font-medium'
                                                        : ($diambil
                                                            ? 'border-primary bg-primary/10 text-primary font-bold shadow-[0_0_0_1px_var(--color-primary)] cursor-pointer'
                                                            : 'border-outline-variant/60 bg-surface-container-lowest text-on-surface-variant hover:border-primary hover:text-primary font-semibold cursor-pointer') }}"
                                                    @if ($lain) title="Diampu oleh {{ $lain }}" @endif>
                                                    <input type="checkbox" name="kelas[]" value="{{ $kelas }}"
                                                        class="sr-only" {{ $diambil ? 'checked' : '' }}
                                                        {{ $lain ? 'disabled' : '' }}>
                                                    <span>{{ $kelas }}</span>
                                                    @if ($lain)
                                                        <span class="w-2 h-2 rounded-full bg-error -mr-1"></span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Footer --}}
                            <div
                                class="p-4 px-6 border-t border-outline-variant/40 flex items-center justify-between gap-3 flex-wrap">
                                <span id="status-pill"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-label-sm font-semibold border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    {{ count($terpilih->assignedKelas()) }} Kelas Siap Disimpan
                                </span>
                                <div class="flex items-center gap-3">
                                    <button type="submit"
                                        class="flex items-center gap-2 h-[42px] px-5 mx-5 rounded-lg bg-primary hover:bg-primary-container active:opacity-90 text-on-primary text-label-md font-bold shadow-md transition-all active:scale-[0.98]">
                                        <span class="material-symbols-outlined text-[18px]">check</span>
                                        Simpan Kelas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        const syncChip = (label) => {
            const input = label.querySelector('input');
            if (input.disabled) return;
            if (input.checked) {
                label.classList.add('border-primary', 'bg-primary/10', 'text-primary', 'font-bold',
                    'shadow-[0_0_0_1px_var(--color-primary)]', 'cursor-pointer');
                label.classList.remove('border-outline-variant/60', 'bg-surface-container-lowest',
                    'text-on-surface-variant', 'font-semibold', 'cursor-pointer', 'hover:border-primary',
                    'hover:text-primary');
            } else {
                label.classList.remove('border-primary', 'bg-primary/10', 'text-primary', 'font-bold',
                    'shadow-[0_0_0_1px_var(--color-primary)]');
                label.classList.add('border-outline-variant/60', 'bg-surface-container-lowest',
                    'text-on-surface-variant', 'font-semibold', 'cursor-pointer', 'hover:border-primary',
                    'hover:text-primary');
            }
        };

        const perbaruiHitung = () => {
            const total = document.querySelectorAll('.kelas-chip input:checked:not(:disabled)').length;
            const badge = document.getElementById('badge-counter');
            const pill = document.getElementById('status-pill');
            const legend = document.getElementById('legend-aktif');
            if (badge) badge.textContent = `${total} kelas diampu`;
            if (pill) pill.innerHTML =
                `<span class="w-2 h-2 rounded-full bg-emerald-500"></span> ${total} Kelas Siap Disimpan`;
            if (legend) legend.textContent = total;

            document.querySelectorAll('[data-count-tingkat]').forEach(el => {
                const t = el.dataset.countTingkat;
                const n = document.querySelectorAll(
                    `[data-group="${t}"] .kelas-chip input:checked:not(:disabled)`).length;
                el.textContent = `${n} Kelas Terpilih`;
                el.className =
                    `text-label-xs px-2 py-0.5 rounded-full font-semibold ${n > 0 ? 'bg-primary-fixed/50 text-on-primary-fixed-variant' : 'bg-surface-container text-on-surface-variant'}`;
            });
        };

        document.querySelectorAll('.kelas-chip input:not(:disabled)').forEach(input => {
            input.addEventListener('change', () => {
                syncChip(input.closest('.kelas-chip'));
                perbaruiHitung();
            });
            syncChip(input.closest('.kelas-chip'));
        });

        document.querySelectorAll('[data-select]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll(
                        `[data-group="${btn.dataset.select}"] .kelas-chip input:not(:disabled)`)
                    .forEach(input => {
                        input.checked = true;
                        syncChip(input.closest('.kelas-chip'));
                    });
                perbaruiHitung();
            });
        });

        document.querySelectorAll('[data-reset]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll(
                        `[data-group="${btn.dataset.reset}"] .kelas-chip input:not(:disabled)`)
                    .forEach(input => {
                        input.checked = false;
                        syncChip(input.closest('.kelas-chip'));
                    });
                perbaruiHitung();
            });
        });

        perbaruiHitung();
    </script>
@endsection
