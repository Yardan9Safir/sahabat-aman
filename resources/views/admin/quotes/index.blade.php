@extends('layouts.admin')

@section('title', 'Kata-Kata Hari Ini - BK')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Quotes & Kata-Kata Hari Ini</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Kelola quotes inspiratif dan pilih satu untuk ditampilkan di homepage.</p>
        </div>
        <a href="{{ route('admin.quotes.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-primary text-on-primary text-label-lg font-semibold shadow-md hover:bg-primary-container hover:text-on-primary-container hover:-translate-y-0.5 transition-all self-start">
            <span class="material-symbols-outlined text-lg">add_notes</span>
            Tambah Quotes
        </a>
    </section>

    {{-- Filter kategori --}}
    <section class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
        <div class="flex flex-wrap items-center gap-1.5 p-1">
            <a href="{{ route('admin.quotes.index') }}" class="px-3 py-1.5 rounded-lg text-label-md {{ !request('category') ? 'font-bold bg-primary text-on-primary shadow-xs' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                Semua ({{ $quotes->count() }})
            </a>
            @foreach(\App\Models\Quote::CATEGORIES as $category)
            <a href="{{ route('admin.quotes.index', ['category' => $category]) }}" class="px-3 py-1.5 rounded-lg text-label-md {{ request('category') === $category ? 'font-bold bg-primary-fixed text-primary' : 'font-medium text-on-surface-variant hover:bg-surface-container' }} transition-colors">
                {{ ucfirst($category) }} ({{ $quotes->where('category_quotes', $category)->count() }})
            </a>
            @endforeach
        </div>
    </section>

    {{-- Grid Quotes --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($quotes as $quote)
        <article class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting custom-shadow-interactive transition-all flex flex-col overflow-hidden {{ $quote->is_daily ? 'ring-2 ring-primary/70' : '' }}">
            {{-- Card header --}}
            <div class="p-5 pb-4 border-b border-outline-variant/40 flex items-start justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-11 h-11 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined">{{ $quote->category_icon }}</span>
                    </div>
                    <div class="min-w-0">
                        <span class="bg-surface-container-high text-primary px-2.5 py-0.5 rounded-full text-label-sm font-semibold inline-block">{{ $quote->category_label }}</span>
                        <h3 class="text-title-md text-on-surface font-bold mt-1.5 truncate">{{ $quote->judul_quotes }}</h3>
                    </div>
                </div>
                @if($quote->is_daily)
                <span class="inline-flex items-center gap-1 bg-primary text-on-primary px-2.5 py-1 rounded-full text-label-sm font-bold shrink-0">
                    <span class="material-symbols-outlined text-sm">today</span>
                    Hari Ini
                </span>
                @endif
            </div>

            {{-- Card body --}}
            <div class="p-5 flex-grow">
                <div class="p-4 bg-surface-container-low/70 rounded-xl border border-outline-variant/40">
                    <p class="text-body-sm text-on-surface italic leading-relaxed">"{{ $quote->isi_quotes }}"</p>
                </div>
            </div>

            {{-- Card footer --}}
            <div class="px-5 py-4 border-t border-outline-variant/40 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-label-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-outline">school</span>
                    <span class="font-semibold text-on-surface truncate">{{ $quote->pembuat?->name ?? 'Guru BK' }}</span>
                    <span>&bull;</span>
                    <span>{{ $quote->created_at?->diffForHumans() ?? '-' }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if($quote->is_daily)
                    <form method="POST" action="{{ route('admin.quotes.update', $quote) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="judul_quotes" value="{{ $quote->judul_quotes }}">
                        <input type="hidden" name="category_quotes" value="{{ $quote->category_quotes }}">
                        <input type="hidden" name="isi_quotes" value="{{ $quote->isi_quotes }}">
                        <button type="submit" class="flex items-center gap-1.5 bg-surface-container text-primary text-label-sm font-semibold px-3.5 py-2 rounded-xl hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-base">event_busy</span>
                            Batalkan
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.quotes.daily', $quote) }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 bg-primary text-on-primary text-label-sm font-semibold px-3.5 py-2 rounded-xl shadow-xs hover:bg-primary-container hover:text-on-primary-container transition-colors">
                            <span class="material-symbols-outlined text-base">today</span>
                            Jadikan Hari Ini
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('admin.quotes.edit', $quote) }}" class="flex items-center gap-1.5 bg-surface-container text-on-surface text-label-sm font-semibold px-3.5 py-2 rounded-xl hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-base">edit</span>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" class="inline" onsubmit="return confirm('Hapus quotes ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-1.5 bg-error-container text-on-error-container text-label-sm font-semibold px-3.5 py-2 rounded-xl hover:bg-error hover:text-on-error transition-colors">
                            <span class="material-symbols-outlined text-base">delete</span>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </article>
        @empty
        <div class="sm:col-span-2 xl:col-span-3 bg-surface-container-lowest p-12 rounded-3xl border border-dashed border-outline-variant/60 text-center">
            <span class="material-symbols-outlined text-outline text-4xl mb-3">format_quote</span>
            <p class="text-headline-sm font-bold text-on-surface">Belum ada quotes</p>
            <p class="text-body-sm text-on-surface-variant mt-1">Tambahkan quotes pertama agar bisa dipilih sebagai kata-kata hari ini.</p>
            <a href="{{ route('admin.quotes.create') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-3 rounded-2xl bg-primary text-on-primary text-label-lg font-semibold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all">
                <span class="material-symbols-outlined text-lg">add_notes</span>
                Tambah Quotes
            </a>
        </div>
        @endforelse
    </section>

</div>
@endsection