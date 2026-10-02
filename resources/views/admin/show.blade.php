@extends('layouts.admin')

@section('title', 'Dialog Curhat - Sahabat Aman')

@section('content')
    <div class="flex flex-col gap-2 max-w-4xl mx-auto">

        {{-- Tampilan Header Percakapan --}}
        <div
            class="bg-surface-container-lowest p-5 rounded-3xl soft-shadow border border-surface-variant/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-title-md font-bold text-on-surface">Pengirim :
                            {{ $curhat->siswa_id === auth()->id()
                                ? $curhat->nama_penerima ?? ($curhat->penerima->name ?? 'Tidak diketahui')
                                : $curhat->nama_pengirim ?? ($curhat->siswa->name ?? 'Siswa') }}
                        </h2>
                    </div>
                    <div class="flex items-center gap-2 text-label-sm text-outline mt-0.5">
                        <span class="flex items-center gap-1 text-primary font-medium">
                            Curhat Tentang : {{ ($curhat->kategori) }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 rounded-xl bg-surface-container text-primary text-label-md font-semibold hover:bg-surface-variant transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
            </a>
        </div>

        {{-- Tampilan Banner Enkripsi + Done Button --}}
        <div
            class="bg-surface-container-lowest p-5 rounded-3xl soft-shadow border border-surface-variant/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            {{-- Banner Enkripsi --}}
            <div
                class="bg-surface-container px-5 py-2.5 rounded-2xl flex items-center gap-2 text-label-sm text-on-surface-variant border border-surface-variant/40">
                <span class="material-symbols-outlined text-primary text-base">lock</span>
                <span><strong>Akses Konseling:</strong> Percakapan ini Terenkripsi</span>
            </div>

            {{-- Tandai Selesai Button --}}
            <div>
                @if ($curhat->status !== 'ditangani' && $curhat->penerima_id === auth()->id() && Auth::user()->isGuru())
                    <form method="POST" action="{{ route('dashboard.handle', $curhat) }}"
                        data-swal-confirm="Tandai selesai dan hapus riwayat chat ini?"
                        data-swal-text="Tindakan ini permanen dan tidak dapat dibatalkan. Seluruh riwayat chat akan dihapus permanen."
                        data-swal-icon="warning" data-swal-confirm-text="Ya, tandai selesai" data-swal-cancel-text="Batal">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary text-label-md font-semibold transition-colors">
                            <span class="material-symbols-outlined text-lg">task_alt</span> Tandai Selesai
                        </button>
                    </form>
                @endif
            </div>

        </div>

        {{-- Tampilan Chat Message --}}
        <div class="bg-surface-container-lowest rounded-3xl soft-shadow border border-surface-variant/40 overflow-hidden">
            <div id="chatList" class="p-6 space-y-6">
                {{-- Balasan --}}
                @forelse($curhat->balasans as $balasan)
                    <div
                        class="flex gap-3.5 max-w-2xl {{ $balasan->pengirim_id === auth()->id() ? 'ml-auto flex-col items-end' : '' }}">
                        <div
                            class="flex flex-col {{ $balasan->pengirim_id === auth()->id() ? 'items-end' : 'items-start' }} space-y-2 flex-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-label-sm font-bold {{ $balasan->pengirim_id === auth()->id() ? 'text-primary' : 'text-on-surface' }}">{{ $balasan->pengirim_id === auth()->id() ? 'Kamu' : $balasan->pengirim->name ?? 'Unknown' }}</span>
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
                    <div id="chatEmpty" class="text-center py-4">
                        <span class="material-symbols-outlined text-outline text-3xl mb-2">chat_bubble</span>
                        <p class="text-body-sm text-outline">Belum ada balasan. Menunggu respon dari pendengar...</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tombol Button + Refresh --}}
        <div class="bg-surface-container-lowest rounded-3xl soft-shadow border border-surface-variant/40 overflow-hidden">
            {{-- Form Reply --}}
            <div class="p-4 bg-surface-container-low border-t border-surface-variant/40">
                @if ($curhat->status !== 'ditangani' && ($curhat->penerima_id === auth()->id() || $curhat->siswa_id === auth()->id()))
                    <form method="POST" action="{{ route('dashboard.reply', $curhat) }}" data-mode="chat"
                        data-target="#chatList" data-empty="#chatEmpty"
                        class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-2 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15 transition-all">
                        @csrf
                        <textarea name="isi" rows="3" required
                            class="w-full resize-none border-none p-2 bg-transparent text-body-md text-on-surface placeholder:text-outline focus:ring-0 outline-none"
                            placeholder="Ketik disini untuk membalas curhat / menerima curhat"></textarea>
                        <div class="flex items-center justify-between pt-2 px-1 border-t border-surface-container-low">
                            <button type="submit" id="replyBtn"
                                class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary text-label-md font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95">
                                <span>Kirim Balasan</span>
                                <span class="material-symbols-outlined text-lg">send</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div
                        class="rounded-2xl bg-error-container text-on-error-container border border-error/30 p-4 text-center">
                        <span class="material-symbols-outlined text-primary text-xl mb-1">check_circle</span>
                        <p class="text-label-md font-semibold">Sudah Ditangani</p>
                        <p class="text-body-sm mt-0.5">Curhat ini sudah selesai ditangani. Percakapan telah berakhir.</p>
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
    @endsection
