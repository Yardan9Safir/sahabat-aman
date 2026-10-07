@extends('layouts.app')

@section('title', 'Tampilan Halaman Login - Guru BK')

@section('content')
@php
    $terpilih = old('pilihan', $quotes->where('tampil_di_login', true)->pluck('id_quotes')->all());
    $terpilih = array_map('intval', (array) $terpilih);
@endphp

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <section>
        <h1 class="text-headline-lg font-bold text-on-surface">Tampilan Halaman Login</h1>
        <p class="text-body-md text-on-surface-variant mt-1">
            Pilih maksimal {{ \App\Models\Quote::LOGIN_DISPLAY_LIMIT }} quotes yang diputar di halaman login.
        </p>
    </section>

    <form method="POST" action="{{ route('guru.login-display.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Master switch --}}
        <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 soft-shadow">
            <label class="flex items-start gap-3 cursor-pointer select-none">
                <input type="checkbox" name="enabled" value="1" id="enabled"
                    class="w-5 h-5 mt-0.5 rounded border-outline-variant text-primary focus:ring-primary/30"
                    {{ old('enabled', $enabled) ? 'checked' : '' }}>
                <span class="flex flex-col">
                    <span class="text-label-lg font-bold text-on-surface">Tampilkan quotes di halaman login</span>
                    <span class="text-label-sm text-on-surface-variant">
                        Kalau dimatikan, blok quotes disembunyikan dan halaman login fokus ke form masuk.
                    </span>
                </span>
            </label>
        </section>

        @error('pilihan')
            <p class="text-sm text-error">{{ $message }}</p>
        @enderror

        {{-- Pemilih quote --}}
        <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 soft-shadow overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant/40 flex items-center justify-between gap-3">
                <h2 class="text-title-md font-bold text-on-surface">Quotes untuk Halaman Login</h2>
                <span class="text-label-sm text-on-surface-variant">
                    Dipilih: <span id="loginQuoteCounter">0</span>/{{ \App\Models\Quote::LOGIN_DISPLAY_LIMIT }}
                </span>
            </div>

            @forelse ($quotes as $quote)
                <label
                    class="flex items-start gap-3 px-5 py-4 border-b border-outline-variant/30 last:border-b-0 hover:bg-surface-container/50 transition-colors cursor-pointer">
                    <input type="checkbox" name="pilihan[]" value="{{ $quote->id_quotes }}" data-quote-checkbox
                        class="w-5 h-5 mt-0.5 rounded border-outline-variant text-primary focus:ring-primary/30 shrink-0"
                        @checked(in_array((int) $quote->id_quotes, $terpilih, true))>
                    <span class="flex-1 min-w-0">
                        <span class="flex flex-wrap items-center gap-2">
                            <span class="text-label-lg font-semibold text-on-surface truncate">{{ $quote->judul_quotes }}</span>
                            <span
                                class="bg-surface-container-high text-primary px-2.5 py-0.5 rounded-md text-label-sm font-semibold">{{ $quote->category_label }}</span>
                            @if ($quote->is_daily)
                                <span
                                    class="bg-primary text-on-primary px-2.5 py-0.5 rounded-full text-label-sm font-semibold">Hari Ini</span>
                            @endif
                        </span>
                        <span class="block text-body-sm text-on-surface-variant mt-1 italic line-clamp-2">{{ $quote->isi_quotes }}</span>
                        <span class="block text-label-sm text-outline mt-1">
                            {{ $quote->displayAuthor() }}
                        </span>
                    </span>
                    <span class="shrink-0">
                        <label class="text-label-sm text-on-surface-variant flex items-center gap-1.5"
                            onclick="event.stopPropagation()">
                            Urutan
                            <input type="number" name="urutan[{{ $quote->id_quotes }}]" min="1"
                                max="{{ \App\Models\Quote::LOGIN_DISPLAY_LIMIT }}" value="{{ $quote->urutan_login }}"
                                data-quote-order
                                class="w-16 bg-surface-container-lowest border-1.5 border-outline-variant rounded-xl px-2.5 py-1.5 text-on-surface text-center focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        </label>
                    </span>
                </label>
            @empty
                <div class="p-10 text-center">
                    <span class="material-symbols-outlined text-outline text-4xl mb-3">format_quote</span>
                    <p class="text-headline-sm font-bold text-on-surface">Belum ada quotes</p>
                    <p class="text-body-sm text-on-surface-variant mt-1">
                        Ajak siswa, konselor sebaya, atau guru untuk membuat quotes terlebih dulu.
                    </p>
                    <a href="{{ route('guru.quotes.create') }}"
                        class="inline-flex items-center gap-2 mt-5 px-5 py-3 rounded-2xl bg-primary text-on-primary text-label-lg font-semibold shadow-md hover:bg-primary-container transition-all">
                        <span class="material-symbols-outlined text-lg">add_notes</span>
                        Tambah Quotes
                    </a>
                </div>
            @endforelse
        </section>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('guru.dashboard') }}"
                class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">Kembali</a>
            <button type="submit"
                class="px-7 py-3.5 rounded-2xl bg-primary text-white text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Simpan Tampilan
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    (() => {
        const limit = {{ \App\Models\Quote::LOGIN_DISPLAY_LIMIT }};
        const boxes = Array.from(document.querySelectorAll('[data-quote-checkbox]'));
        const counter = document.getElementById('loginQuoteCounter');

        const sync = () => {
            const checked = boxes.filter((box) => box.checked);

            if (checked.length > limit) {
                checked.slice(limit).forEach((box) => {
                    box.checked = false;
                    box.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }

            counter.textContent = String(boxes.filter((box) => box.checked).length);
        };

        boxes.forEach((box) => box.addEventListener('change', sync));
        sync();
    })();
</script>
@endsection
