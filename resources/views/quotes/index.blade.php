@extends('layouts.app')

@section('title', 'Quotes - Sahabat Aman')

@section('content')
@php
    $isGuru = auth()->user()->isGuru();
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Quotes & Kata-Kata Hari Ini</h1>
            <p class="text-body-md text-on-surface-variant mt-1">
                Koleksi quotes dari siswa, konselor sebaya, dan guru.
                @if ($isGuru)
                    Quote yang tampil di halaman login diatur di menu
                    <a href="{{ route('admin.login-display.index') }}" class="font-semibold text-primary hover:underline">Tampilan Login</a>.
                @endif
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 self-start">
            @if ($isGuru)
                <a href="{{ route('admin.login-display.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-semibold hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-lg">tune</span>
                    Atur Tampilan Login
                </a>
            @endif
            <a href="{{ route('quotes.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-primary text-on-primary text-label-lg font-semibold shadow-md hover:bg-primary-container hover:text-on-primary-container hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-lg">add_notes</span>
                Tambah Quotes
            </a>
        </div>
    </section>

    {{-- Filter kategori --}}
    <section class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 soft-shadow">
        <div class="flex flex-wrap items-center gap-1.5 p-1">
            <a href="{{ route('quotes.index', array_filter(['mine' => $mine ? 1 : null])) }}"
                class="px-3 py-1.5 rounded-lg text-label-md {{ !$category ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                Semua ({{ $total }})
            </a>
            @foreach (\App\Models\Quote::CATEGORIES as $item)
                <a href="{{ route('quotes.index', array_filter(['category' => $item, 'mine' => $mine ? 1 : null])) }}"
                    class="px-3 py-1.5 rounded-lg text-label-md {{ $category === $item ? 'font-bold bg-primary-fixed text-primary' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                    {{ ucfirst($item) }} ({{ $counts[$item] ?? 0 }})
                </a>
            @endforeach
            <span class="w-px h-6 bg-outline-variant/50 mx-1 self-stretch"></span>
            <a href="{{ route('quotes.index', array_filter(['category' => $category, 'mine' => $mine ? null : 1])) }}"
                class="px-3 py-1.5 rounded-lg text-label-md {{ $mine ? 'font-bold bg-tertiary-container text-on-tertiary-container shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                Milik Saya
            </a>
        </div>
    </section>

    {{-- Tabel quotes --}}
    <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 soft-shadow overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant/40 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-title-md font-bold text-on-surface">Daftar Quotes</h2>
            <p class="text-label-sm text-on-surface-variant">
                @if ($category || $mine)
                    Menampilkan hasil filter. <a href="{{ route('quotes.index') }}" class="font-semibold text-primary hover:underline">Reset filter</a>
                @else
                    Urut dari quotes terbaru.
                @endif
            </p>
        </div>

        <div class="p-5 overflow-x-auto" data-datatable>
            {!! $dataTable->table(['class' => 'dt-quotes w-full']) !!}
        </div>
    </section>

    <noscript>
        <p class="text-body-sm text-on-surface-variant">
            Tabel ini butuh JavaScript aktif. Kamu tetap bisa
            <a href="{{ route('quotes.create') }}" class="font-semibold text-primary hover:underline">menambah quotes baru</a>
            tanpa JavaScript.
        </p>
    </noscript>

</div>

{{-- Konfigurasi DataTables, dibaca oleh resources/js/datatables.js --}}
<script type="application/json" data-datatable-config>{!! $dataTable->generateJson() !!}</script>
@endsection
