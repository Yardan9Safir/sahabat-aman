@extends('layouts.admin')

@section('title', 'Kelola Quotes - Guru BK')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Kelola Quotes</h1>
            <p class="text-body-md text-on-surface-variant mt-1">
                Tambah, ubah, dan hapus quotes dari siswa, konselor sebaya, maupun guru.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.quotes.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-primary text-on-primary text-label-lg font-semibold shadow-md hover:bg-primary-container hover:text-on-primary-container hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-lg">add_notes</span>
                Tambah Quotes
            </a>
        </div>
    </section>

    {{-- Filter kategori --}}
    <section class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 soft-shadow">
        <div class="flex flex-wrap items-center gap-1.5 p-1">
            Kategori Quotes :
            <a href="{{ route('admin.quotes.index') }}"
                class="px-3 py-1.5 rounded-lg text-label-md {{ !$category ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                Semua ({{ $total }})
            </a>
            @foreach (\App\Models\Quote::CATEGORIES as $item)
                <a href="{{ route('admin.quotes.index', array_filter(['category' => $item])) }}"
                    class="px-3 py-1.5 rounded-lg text-label-md {{ $category === $item ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                    {{ ucfirst($item) }} ({{ $counts[$item] ?? 0 }})
                </a>
            @endforeach
        </div>
    </section>

    {{-- Tabel quotes --}}
    <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 soft-shadow overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant/40 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-title-md font-bold text-on-surface">Daftar Quotes</h2>
            <p class="text-label-sm text-on-surface-variant">
                @if ($category)
                    Menampilkan hasil filter. <a href="{{ route('admin.quotes.index') }}" class="font-semibold text-primary hover:underline">Reset filter</a>
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
            <a href="{{ route('admin.quotes.create') }}" class="font-semibold text-primary hover:underline">menambah quotes baru</a>
            tanpa JavaScript.
        </p>
    </noscript>

</div>

{{-- Konfigurasi DataTables, dibaca oleh resources/js/datatables.js --}}
<script type="application/json" data-datatable-config>{!! $dataTable->generateJson() !!}</script>
@endsection
