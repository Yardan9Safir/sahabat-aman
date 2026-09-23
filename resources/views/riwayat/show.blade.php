@extends('layouts.app')

@section('title', 'Detail Riwayat - Sahabat Aman')

@section('content')
    <div class="flex flex-col gap-6 max-w-4xl mx-auto">

        {{-- Back Link --}}
        <a href="{{ route('riwayat') }}"
            class="inline-flex items-center gap-1.5 text-label-md text-primary hover:underline font-semibold">
            <span class="material-symbols-outlined text-base">arrow_back</span> Kembali ke Riwayat
        </a>

        {{-- Conversation Header --}}
        <div
            class="bg-surface-container-lowest p-5 rounded-3xl soft-shadow border border-surface-variant/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-title-md font-bold text-on-surface">
                            {{ $curhat->nama_penerima ?? ($curhat->penerima->name ?? 'Tidak diketahui') }}</h2>
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-secondary-container text-on-secondary-container' : 'bg-tertiary-container text-on-tertiary-container' }} text-label-sm font-semibold">
                            <span class="material-symbols-outlined text-xs">{{ $curhat->tujuanIcon }}</span>
                            {{ $curhat->tujuanLabel }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-label-sm text-outline mt-0.5">
                        <span class="flex items-center gap-1 text-primary font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            {{ ucfirst($curhat->status) }}
                        </span>
                        <span>&bull;</span>
                        <span>Topik: {{ ucfirst($curhat->kategori) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Timeline --}}
        <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
            <h2 class="text-title-md font-bold text-on-surface mb-4">Status Curhat</h2>
            <div class="space-y-3">
                @foreach (['terkirim' => 'Terkirim', 'dilihat' => 'Dilihat', 'dibalas' => 'Dibalas', 'ditangani' => 'Ditangani'] as $statusKey => $statusLabel)
                    @php $active = array_search($curhat->status, ['terkirim', 'dilihat', 'dibalas', 'ditangani']) >= array_search($statusKey, ['terkirim', 'dilihat', 'dibalas', 'ditangani']); @endphp
                    <div class="flex items-center gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $active ? 'bg-primary text-on-primary' : 'bg-surface-container text-outline' }}">
                            @if ($active)
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

        {{-- Conversation Thread --}}
        <div class="bg-surface-container-lowest rounded-3xl soft-shadow border border-surface-variant/40 overflow-hidden">
            <div class="p-6 space-y-6">
                {{-- Date Divider --}}
                <div class="flex items-center justify-center">
                    <span class="px-3 py-1 rounded-full bg-surface-container-high/60 text-outline text-label-sm">
                        {{ $curhat->created_at->translatedFormat('d M Y') }}
                    </span>
                </div>

                {{-- Student's Original Vent --}}
                <div class="flex flex-col items-end max-w-xl ml-auto">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-label-sm text-outline">{{ $curhat->created_at->translatedFormat('H:i') }}
                            WIB</span>
                        <span class="text-label-sm font-bold text-on-surface">Kamu</span>
                    </div>
                    <div
                        class="bg-primary-container text-on-primary-container p-4 rounded-2xl rounded-tr-xs shadow-sm space-y-2">
                        <div
                            class="flex items-center gap-2 pb-1.5 border-b border-on-primary-container/20 text-on-primary-container text-label-sm">
                            <span class="material-symbols-outlined text-sm">psychology</span>
                            <span>{{ ucfirst($curhat->kategori) }}</span>
                        </div>
                        <p class="text-body-md leading-relaxed text-on-primary-container">{{ $curhat->pesan }}</p>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-label-sm text-outline">
                        <span class="material-symbols-outlined text-xs text-primary">done_all</span>
                        <span>Terkirim</span>
                    </div>
                </div>

                {{-- Balasan --}}
                @forelse ($curhat->balasans as $balasan)
                    <div
                        class="flex items-start gap-3.5 max-w-2xl {{ $balasan->pengirim_id === auth()->id() ? 'ml-auto flex-col items-end' : '' }}">
                        @if ($balasan->pengirim_id !== auth()->id())
                            <div
                                class="w-9 h-9 rounded-xl bg-surface-container-high flex-shrink-0 mt-1 shadow-xs flex items-center justify-center text-primary font-bold text-xs">
                                {{ strtoupper(substr($balasan->pengirim->name ?? 'U', 0, 2)) }}
                            </div>
                        @endif
                        <div
                            class="flex flex-col {{ $balasan->pengirim_id === auth()->id() ? 'items-end' : 'items-start' }} space-y-2 flex-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-label-sm font-bold {{ $balasan->pengirim_id === auth()->id() ? 'text-primary' : 'text-on-surface' }}">{{ $balasan->pengirim->name ?? 'Unknown' }}</span>
                                <span
                                    class="text-label-sm text-outline">{{ $balasan->created_at->translatedFormat('H:i') }}
                                    WIB</span>
                            </div>
                            <div
                                class="{{ $balasan->pengirim_id === auth()->id() ? 'bg-primary-container text-on-primary-container rounded-2xl rounded-tr-xs' : 'bg-surface-container-lowest border border-outline-variant/30 text-on-surface rounded-2xl rounded-tl-xs' }} p-4 shadow-sm">
                                <p class="text-body-md leading-relaxed">{{ $balasan->isi }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <span class="material-symbols-outlined text-outline text-3xl mb-2">chat_bubble</span>
                        <p class="text-body-sm text-outline">Belum ada balasan. Menunggu respon dari
                            {{ $curhat->tujuanLabel }}...</p>
                    </div>
                @endforelse
            </div>

            {{-- Composer --}}
            <div class="p-4 bg-surface-container-low border-t border-surface-variant/40">
                @if ($curhat->status !== 'ditangani')
                    <form id="replyForm" data-endpoint="{{ route('dashboard.reply', $curhat) }}" data-mode="chat"
                        data-target=".space-y-6"
                        class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-2 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15 transition-all">
                        @csrf
                        <textarea name="isi" rows="3" required
                            class="w-full resize-none border-none p-2 bg-transparent text-body-md text-on-surface placeholder:text-outline focus:ring-0 outline-none"
                            placeholder="Tuliskan balasan untuk {{ $curhat->nama_penerima ?? ($curhat->penerima->name ?? 'penerima') }} di sini..."></textarea>
                        <div class="flex items-center justify-between pt-2 px-1 border-t border-surface-container-low">
                            <span class="text-label-sm text-outline hidden sm:inline">Tekan Enter untuk kirim</span>
                            <button type="submit" id="replyBtn"
                                class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary text-label-md font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95">
                                <span>Kirim Balasan</span>
                                <span class="material-symbols-outlined text-lg">send</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="rounded-2xl bg-surface-container-low border border-outline-variant/40 p-4 text-center">
                        <span class="material-symbols-outlined text-primary text-xl mb-1">check_circle</span>
                        <p class="text-label-md font-semibold text-on-surface">Curhat ini sudah ditangani</p>
                        <p class="text-body-sm text-outline">Percakapan telah selesai. Kamu tidak bisa mengirim balasan lagi.</p>
                    </div>
                @endif
            </div>

            {{-- Tombol Refresh --}}
            <div class="flex justify-center">
                <button onclick="location.reload()"
                    class="px-4 py-2 rounded-xl text-on-surface-variant text-label-md font-semibold flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">refresh</span> Muat Ulang
                </button>
            </div>
        </div>
    </div>
@endsection