@extends('layouts.admin')

@section('title', 'Detail Curhat Siswa - BK')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Back Link --}}
        <a href="{{ route('admin.index') }}"
            class="inline-flex items-center gap-1.5 text-label-md text-primary hover:underline font-semibold">
            <span class="material-symbols-outlined text-base">arrow_back</span> Kembali ke Ikhtisar
        </a>

        {{-- Curhat Header --}}
        <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-headline-sm font-bold text-on-surface"> Pengirim : {{ $curhat->nama_pengirim ?? ($curhat->siswa->name ?? 'Siswa') }}</h1>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-secondary-container text-on-secondary-container' : 'bg-primary-container text-on-primary-container' }} text-label-sm font-semibold"> Tujuan : 
                                {{ $curhat->tujuan === 'guru' ? 'Guru BK' : 'Konselor Sebaya' }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->status === 'terkirim' ? 'bg-tertiary-container text-on-tertiary-container' : ($curhat->status === 'dibalas' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container text-on-surface-variant') }} text-label-sm font-semibold"> Status : 
                                {{ $curhat->status === 'terkirim' ? 'Belum Dibalas' : ucfirst($curhat->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->role === 'guru' && $curhat->penerima_id === Auth::id() && $curhat->status !== 'ditangani')
                    <form method="POST" action="{{ route('dashboard.handle', $curhat) }}"
                            onsubmit="return confirm('Tandai selesai dan hapus riwayat chat ini? Tindakan ini permanen dan tidak dapat dibatalkan.');">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary font-label-md font-semibold px-4 py-2 rounded-xl shadow-xs transition-all">
                            <span class="material-symbols-outlined text-base">task_alt</span>
                            <span>Tandai Selesai</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Curhat Content --}}
        <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary">edit_note</span>
                <h2 class="text-title-md font-bold text-on-surface">Isi Curhat</h2>
            </div>
            <div class="p-4 bg-surface-container-low rounded-2xl border border-outline-variant/40">
                <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $curhat->pesan }}</p>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <p class="text-label-sm text-outline">Kategori</p>
                    <p class="text-label-md font-bold text-on-surface mt-1">{{ ucfirst($curhat->kategori) }}</p>
                </div>
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <p class="text-label-sm text-outline">Waktu Kirim</p>
                    <p class="text-label-md font-bold text-on-surface mt-1">
                        {{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <p class="text-label-sm text-outline">Status</p>
                    <p class="text-label-md font-bold text-on-surface mt-1">{{ $curhat->status === 'terkirim' ? 'Belum Dibalas' : ucfirst($curhat->status) }}</p>
                </div>
            </div>
        </div>

        {{-- Reply History --}}
        <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
            <h2 class="text-title-md font-bold text-on-surface mb-4">Riwayat Balasan</h2>
            <div id="balasanList" class="space-y-4">
                @forelse($curhat->balasans as $balasan)
                    <div
                        class="rounded-2xl {{ $balasan->pengirim_id === auth()->id() ? 'bg-primary-fixed/40 border border-primary/20' : 'bg-surface-container-low border border-outline-variant/30' }} p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full {{ $balasan->pengirim_id === auth()->id() ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-primary' }} flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($balasan->pengirim->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-label-md font-bold text-on-surface">
                                        {{ $balasan->pengirim->name ?? 'Unknown' }}</p>
                                    <span class="text-label-sm text-outline">{{ $balasan->pengirimRoleLabel }}</span>
                                </div>
                            </div>
                            <span
                                class="text-label-sm text-outline">{{ $balasan->created_at->translatedFormat('d M Y, H:i') }}</span>
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

        {{-- Reply Form / Handled State --}}
        @if ($curhat->status === 'ditangani')
            <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
                <div class="rounded-2xl bg-error-container text-on-error-container border border-error/30 p-5 text-center">
                    <span class="material-symbols-outlined text-3xl mb-2 inline-block">check_circle</span>
                    <h2 class="text-title-md font-bold">Sudah Ditangani</h2>
                    <p class="text-body-sm mt-1">Curhat ini sudah selesai ditangani dan tidak dapat dibalas lagi.</p>
                </div>
            </div>
        @elseif ($curhat->status !== 'ditangani' && Auth::user()->role === 'guru' && $curhat->penerima_id === Auth::id())
            <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
                <h2 class="text-title-md font-bold text-on-surface mb-4">Balas Curhat</h2>
                <form id="replyForm" data-endpoint="{{ route('dashboard.reply', $curhat) }}"
                    class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-2 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15 transition-all">
                    @csrf
                    <textarea name="isi" rows="4" required
                        class="w-full resize-none border-none p-3 bg-transparent text-body-md text-on-surface placeholder:text-outline focus:ring-0 outline-none"
                        placeholder="Tulis balasan yang empati dan membantu..."></textarea>
                    <div class="flex items-center justify-between pt-2 px-1 border-t border-surface-container-low">
                        <span class="text-label-sm text-outline">Tekan Enter untuk kirim</span>
                        <button type="submit" id="replyBtn"
                            class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary text-label-md font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95">
                            <span>Kirim Balasan</span>
                            <span class="material-symbols-outlined text-lg">send</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Tombol Refresh --}}
        <div class="flex justify-center">
            <button onclick="location.reload()"
                class="px-4 py-2 rounded-xl text-on-surface-variant text-label-md font-semibold flex items-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-lg">refresh</span> Muat Ulang
            </button>
        </div>

    </div>
@endsection
