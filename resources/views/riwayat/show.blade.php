@extends('layouts.app')

@section('title', 'Detail Riwayat - Sahabat Aman')

@section('content')
<div class="flex flex-col gap-6 max-w-4xl mx-auto">
    {{-- Back Link --}}
    <a href="{{ route('riwayat') }}" class="inline-flex items-center gap-1.5 text-label-md text-primary hover:underline font-semibold">
        <span class="material-symbols-outlined text-base">arrow_back</span> Kembali ke Riwayat
    </a>

    {{-- Curhat Header --}}
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-headline-sm font-bold text-on-surface">Kategori Curhat : {{ ucfirst($curhat->kategori) }}</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-[#ECE8F6] text-[#534675]' : 'bg-[#E5F6EB] text-[#21633A]' }} text-label-sm font-semibold">
                            {{ $curhat->tujuan === 'guru' ? 'Guru BK' : 'Teman Sebaya' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->status === 'terkirim' ? 'bg-[#FEF3E2] text-[#964F06]' : ($curhat->status === 'dibalas' ? 'bg-[#E4F5EB] text-[#1B5E3B]' : 'bg-surface-container text-on-surface-variant') }} text-label-sm font-semibold">
                            Status : {{ ucfirst($curhat->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Curhat Content --}}
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-title-md font-bold text-on-surface">Isi Curhat</h2>
        </div>
        <div class="p-4 bg-surface-container-low rounded-2xl border border-outline-variant/40">
            <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $curhat->pesan }}</p>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div class="p-3 rounded-xl bg-surface-container-low">
                <p class="text-label-sm text-outline">Penerima</p>
                <p class="text-label-md font-bold text-on-surface mt-1">{{ $curhat->nama_penerima ?? $curhat->penerima->name ?? '-' }}</p>
            </div>
            <div class="p-3 rounded-xl bg-surface-container-low">
                <p class="text-label-sm text-outline">Waktu Kirim</p>
                <p class="text-label-md font-bold text-on-surface mt-1">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <h2 class="text-title-md font-bold text-on-surface mb-4">Status Curhat</h2>
        <div class="space-y-3">
            @foreach(['terkirim' => 'Terkirim', 'dilihat' => 'Dilihat', 'dibalas' => 'Dibalas', 'ditangani' => 'Ditangani'] as $statusKey => $statusLabel)
                @php $active = array_search($curhat->status, ['terkirim','dilihat','dibalas','ditangani']) >= array_search($statusKey, ['terkirim','dilihat','dibalas','ditangani']); @endphp
                <div class="flex items-center gap-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $active ? 'bg-primary text-on-primary' : 'bg-surface-container text-outline' }}">
                        @if($active)
                            <span class="material-symbols-outlined text-sm">check</span>
                        @else
                            {{ $loop->index + 1 }}
                        @endif
                    </div>
                    <div class="flex-1 rounded-2xl {{ $active ? 'bg-primary-fixed/40 border border-primary/20' : 'bg-surface-container-low border border-outline-variant/30' }} p-3">
                        <p class="text-sm font-bold {{ $active ? 'text-primary' : 'text-outline' }}">{{ $statusLabel }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Reply Form --}}
    @if($curhat->status !== 'ditangani')
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <h2 class="text-title-md font-bold text-on-surface mb-4">Balas Curhat</h2>
        <form id="replyForm" data-endpoint="{{ route('dashboard.reply', $curhat) }}" class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-2 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15 transition-all">
            @csrf
            <textarea name="isi" rows="4" required class="w-full resize-none border-none p-3 bg-transparent text-body-md text-on-surface placeholder:text-outline focus:ring-0 outline-none" placeholder="Tulis balasan..."></textarea>
            <div class="flex items-center justify-between pt-2 px-1 border-t border-surface-container-low">
                <span class="text-label-sm text-outline">Tekan Enter untuk kirim</span>
                <button type="submit" id="replyBtn" class="px-5 py-2.5 rounded-xl bg-primary-container hover:bg-primary text-on-primary text-label-md font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95">
                    <span>Kirim</span>
                    <span class="material-symbols-outlined text-lg">send</span>
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Tombol Refresh --}}
    <div class="flex justify-center">
        <button onclick="location.reload()" class="px-4 py-2 rounded-xl bg-surface-container text-on-surface-variant hover:bg-surface-container-high text-label-md font-semibold flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-lg">refresh</span> Muat Ulang
        </button>
    </div>

    {{-- Reply History --}}
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <h2 class="text-title-md font-bold text-on-surface mb-4">Riwayat Balasan</h2>
        <div id="balasanList" class="space-y-4">
            @forelse($curhat->balasans as $balasan)
            <div class="rounded-2xl {{ $balasan->pengirim_id === auth()->id() ? 'bg-primary-fixed/40 border border-primary/20' : 'bg-surface-container-low border border-outline-variant/30' }} p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full {{ $balasan->pengirim_id === auth()->id() ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-primary' }} flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr($balasan->pengirim->name ?? 'U', 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-label-md font-bold text-on-surface">{{ $balasan->pengirim->name ?? 'Unknown' }}</p>
                            <span class="text-label-sm text-outline capitalize">{{ $balasan->pengirim_role }}</span>
                        </div>
                        <span class="text-label-sm text-outline">{{ $balasan->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
                <p class="text-body-md text-on-surface leading-relaxed">{{ $balasan->isi }}</p>
            </div>
            @empty
            <div class="text-center py-6">
                <span class="material-symbols-outlined text-outline text-3xl mb-2">chat_bubble</span>
                <p class="text-body-sm text-outline">Belum ada balasan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
