@extends('layouts.admin')

@section('title', 'Detail Curhat Siswa - RuangTeduh BK')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Link --}}
    <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-1.5 text-label-md text-primary hover:underline font-semibold">
        <span class="material-symbols-outlined text-base">arrow_back</span> Kembali ke Ikhtisar
    </a>

    {{-- Curhat Header --}}
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary font-bold text-lg">
                    {{ strtoupper(substr($curhat->siswa->name ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-headline-sm font-bold text-on-surface">{{ $curhat->siswa->name ?? 'Siswa' }}</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->tujuan === 'guru' ? 'bg-[#ECE8F6] text-[#534675]' : 'bg-[#E5F6EB] text-[#21633A]' }} text-label-sm font-semibold">
                            {{ $curhat->tujuan === 'guru' ? 'Guru BK' : 'Peer Counselor' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $curhat->status === 'terkirim' ? 'bg-[#FEF3E2] text-[#964F06]' : ($curhat->status === 'dibalas' ? 'bg-[#E4F5EB] text-[#1B5E3B]' : 'bg-surface-container text-on-surface-variant') }} text-label-sm font-semibold">
                            {{ ucfirst($curhat->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @if(Auth::user()->role === 'guru' && $curhat->penerima_id === Auth::id() && $curhat->status !== 'ditangani')
            <form method="POST" action="{{ route('dashboard.handle', $curhat) }}">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 bg-primary hover:bg-primary-container text-on-primary font-label-md font-semibold px-4 py-2 rounded-xl shadow-xs transition-all">
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
                <p class="text-label-md font-bold text-on-surface mt-1">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div class="p-3 rounded-xl bg-surface-container-low">
                <p class="text-label-sm text-outline">Status</p>
                <p class="text-label-md font-bold text-on-surface mt-1">{{ ucfirst($curhat->status) }}</p>
            </div>
        </div>
    </div>

    {{-- Reply Form --}}
    @if($curhat->status !== 'ditangani')
    <div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
        <h2 class="text-title-md font-bold text-on-surface mb-4">Balas Curhat</h2>
        <form id="replyForm" class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-2 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15 transition-all">
            @csrf
            <textarea name="isi" rows="4" required class="w-full resize-none border-none p-3 bg-transparent text-body-md text-on-surface placeholder:text-outline focus:ring-0 outline-none" placeholder="Tulis balasan yang empati dan membantu..."></textarea>
            <div class="flex items-center justify-between pt-2 px-1 border-t border-surface-container-low">
                <span class="text-label-sm text-outline">Tekan Enter untuk kirim</span>
                <button type="submit" id="replyBtn" class="px-5 py-2.5 rounded-xl bg-primary-container hover:bg-primary text-on-primary text-label-md font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95">
                    <span>Kirim Balasan</span>
                    <span class="material-symbols-outlined text-lg">send</span>
                </button>
            </div>
        </form>
    </div>
    @endif

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
                    </div>
                    <span class="text-label-sm text-outline">{{ $balasan->created_at->translatedFormat('d M Y, H:i') }}</span>
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
            const list = document.getElementById('balasanList');
            const emptyMsg = list.querySelector('.text-center');
            if (emptyMsg) emptyMsg.remove();

            const div = document.createElement('div');
            div.className = 'rounded-2xl bg-primary-fixed/40 border border-primary/20 p-4';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center text-xs font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                            <p class="text-label-md font-bold text-on-surface">${data.balasan.pengirim_name}</p>
                            <span class="text-label-sm text-outline capitalize">${data.balasan.pengirim_role}</span>
                        </div>
                    </div>
                    <span class="text-label-sm text-outline">${data.balasan.created_at}</span>
                </div>
                <p class="text-body-md text-on-surface leading-relaxed">${data.balasan.isi}</p>
            `;
            list.appendChild(div);
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
