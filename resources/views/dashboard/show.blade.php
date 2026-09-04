@extends('layouts.app')

@section('title', 'Dialog Curhat - Sahabat Aman')

@section('content')
<div class="flex flex-col gap-6 max-w-4xl mx-auto">

    {{-- Conversation Header --}}
    <div class="bg-surface-container-lowest p-5 rounded-3xl soft-shadow border border-surface-variant/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary font-bold text-lg">
                {{ strtoupper(substr($curhat->nama_penerima ?? $curhat->penerima->name ?? '??', 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-title-md font-bold text-on-surface">{{ $curhat->nama_penerima ?? $curhat->penerima->name ?? 'Tidak diketahui' }}</h2>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-[#ECE8F6] text-[#534675]' : 'bg-[#E5F6EB] text-[#21633A]' }} text-label-sm font-semibold">
                        {{ $curhat->tujuan === 'guru' ? 'Guru BK' : 'Peer Counselor' }}
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
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-surface-container text-primary text-label-md font-semibold hover:bg-surface-variant transition-colors flex items-center gap-1.5">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
        </a>
    </div>

    {{-- Confidentiality Banner --}}
    <div class="bg-surface-container px-5 py-2.5 rounded-2xl flex items-center gap-2 text-label-sm text-on-surface-variant border border-surface-variant/40">
        <span class="material-symbols-outlined text-primary text-base">lock</span>
        <span><strong>Akses Konseling:</strong> Percakapan ini hanya dapat dilihat oleh kamu dan {{ $curhat->nama_penerima ?? $curhat->penerima->name ?? 'penerima' }}.</span>
    </div>

    {{-- Chat Message Thread --}}
    <div class="bg-surface-container-lowest rounded-3xl soft-shadow border border-surface-variant/40 overflow-hidden">
        <div class="p-6 space-y-6">
            {{-- Date Divider --}}
            <div class="flex items-center justify-center">
                <span class="px-3 py-1 rounded-full bg-surface-container-high/60 text-outline text-label-sm">
                    {{ $curhat->created_at->translatedFormat('d M Y') }}
                </span>
            </div>

            {{-- User's Original Vent --}}
            <div class="flex flex-col items-end max-w-xl ml-auto">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-label-sm text-outline">{{ $curhat->created_at->translatedFormat('H:i') }} WIB</span>
                    <span class="text-label-sm font-bold text-on-surface">Kamu</span>
                </div>
                <div class="bg-primary-container text-on-primary p-4 rounded-2xl rounded-tr-xs shadow-sm space-y-2">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-on-primary-container/20 text-on-primary-container text-label-sm">
                        <span class="material-symbols-outlined text-sm">psychology</span>
                        <span>{{ ucfirst($curhat->kategori) }}</span>
                    </div>
                    <p class="text-body-md leading-relaxed text-white">{{ $curhat->pesan }}</p>
                </div>
                <div class="flex items-center gap-2 mt-1 text-label-sm text-outline">
                    <span class="material-symbols-outlined text-xs text-primary">done_all</span>
                    <span>Terkirim terenkripsi</span>
                </div>
            </div>

            {{-- Balasan --}}
            @forelse($curhat->balasans as $balasan)
            <div class="flex items-start gap-3.5 max-w-2xl {{ $balasan->pengirim_id === auth()->id() ? 'ml-auto flex-col items-end' : '' }}">
                @if($balasan->pengirim_id !== auth()->id())
                <div class="w-9 h-9 rounded-xl bg-surface-container-high flex-shrink-0 mt-1 shadow-xs flex items-center justify-center text-primary font-bold text-xs">
                    {{ strtoupper(substr($balasan->pengirim->name ?? 'U', 0, 2)) }}
                </div>
                @endif
                <div class="flex flex-col {{ $balasan->pengirim_id === auth()->id() ? 'items-end' : 'items-start' }} space-y-2 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-label-sm font-bold {{ $balasan->pengirim_id === auth()->id() ? 'text-primary' : 'text-on-surface' }}">{{ $balasan->pengirim->name ?? 'Unknown' }}</span>
                        <span class="text-label-sm text-outline">{{ $balasan->created_at->translatedFormat('H:i') }} WIB</span>
                    </div>
                    <div class="{{ $balasan->pengirim_id === auth()->id() ? 'bg-primary-container text-on-primary rounded-2xl rounded-tr-xs' : 'bg-surface-container-lowest border border-outline-variant/30 text-on-surface rounded-2xl rounded-tl-xs' }} p-4 shadow-sm">
                        <p class="text-body-md leading-relaxed">{{ $balasan->isi }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <span class="material-symbols-outlined text-outline text-3xl mb-2">chat_bubble</span>
                <p class="text-body-sm text-outline">Belum ada balasan. Menunggu respon dari pendengar...</p>
            </div>
            @endforelse
        </div>

        {{-- Composer --}}
        <div class="p-4 bg-surface-container-low border-t border-surface-variant/40">
            @if($curhat->status !== 'ditangani')
            <form id="replyForm" class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-2 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15 transition-all">
                @csrf
                <textarea name="isi" rows="3" required class="w-full resize-none border-none p-2 bg-transparent text-body-md text-on-surface placeholder:text-outline focus:ring-0 outline-none" placeholder="Tuliskan apa yang sedang membebani pikiranmu di sini... Ruang ini aman untukmu."></textarea>
                <div class="flex items-center justify-between pt-2 px-1 border-t border-surface-container-low">
                    <span class="text-label-sm text-outline hidden sm:inline">Tekan Enter untuk kirim</span>
                    <button type="submit" id="replyBtn" class="px-5 py-2.5 rounded-xl bg-primary-container hover:bg-primary text-on-primary text-label-md font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95">
                        <span>Kirim Balasan</span>
                        <span class="material-symbols-outlined text-lg">send</span>
                    </button>
                </div>
            </form>
            @else
            <div class="rounded-2xl bg-surface-container-low border border-outline-variant/40 p-4 text-center">
                <span class="material-symbols-outlined text-primary text-xl mb-1">check_circle</span>
                <p class="text-label-md font-semibold text-on-surface">Curhat ini sudah ditandai selesai</p>
                <p class="text-body-sm text-outline">Percakapan ini telah berakhir dengan baik.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('replyForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const btn = document.getElementById('replyBtn');
    const textarea = form.querySelector('textarea[name="isi"]');
    const isi = textarea.value.trim();
    if (!isi) return;

    btn.disabled = true;
    btn.textContent = 'Mengirim...';

    try {
        const res = await fetch('{{ route("dashboard.reply", $curhat) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ isi }),
        });
        const data = await res.json();
        if (data.success) {
            const thread = document.querySelector('.space-y-6');
            const emptyMsg = thread.querySelector('.text-center');
            if (emptyMsg) emptyMsg.remove();

            const div = document.createElement('div');
            div.className = 'flex flex-col items-end max-w-xl ml-auto';
            div.innerHTML = `
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-label-sm text-outline">${data.balasan.created_at}</span>
                    <span class="text-label-sm font-bold text-on-surface">Kamu</span>
                </div>
                <div class="bg-primary-container text-on-primary p-4 rounded-2xl rounded-tr-xs shadow-sm">
                    <p class="text-body-md leading-relaxed text-white">${data.balasan.isi}</p>
                </div>
                <div class="flex items-center gap-2 mt-1 text-label-sm text-outline">
                    <span class="material-symbols-outlined text-xs text-primary">done_all</span>
                    <span>Terkirim terenkripsi</span>
                </div>
            `;
            thread.appendChild(div);
            textarea.value = '';
        }
    } catch (err) {
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span>Kirim Balasan</span><span class="material-symbols-outlined text-lg">send</span>';
    }
});
</script>
@endsection
