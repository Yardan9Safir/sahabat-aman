@extends('layouts.app')

@section('title', 'Detail Curhat Masuk')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">Curhat masuk</p>
                <h1 class="mt-2 text-3xl font-black text-[#214D41]">{{ $curhat->siswa->name }}</h1>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
        </div>

        <div class="mt-6 rounded-2xl bg-[#F9F6F2] p-5 ring-1 ring-[#E5DECF]">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#6D7E79]">Isi curhat</p>
            <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#3C504B]">{{ $curhat->pesan }}</p>
        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Kategori</p>
                <p class="mt-2 font-bold text-[#214D41]">{{ ucfirst($curhat->kategori) }}</p>
            </div>
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Tujuan</p>
                <p class="mt-2 font-bold text-[#214D41]">{{ $curhat->tujuan === 'siswa' ? 'Siswa Lain' : 'Guru' }}</p>
            </div>
            <div class="rounded-2xl bg-[#F5F2EA] p-4">
                <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Waktu</p>
                <p class="mt-2 font-bold text-[#214D41]">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <h2 class="text-xl font-bold text-[#214D41]">Balas curhat</h2>
        <form id="replyForm" class="mt-5 space-y-4">
            @csrf
            <textarea name="isi" rows="5" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none" placeholder="Tulis balasan yang empati dan membantu..."></textarea>
            <div class="flex items-center gap-3">
                <button type="submit" id="replyBtn" class="rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white">Kirim balasan</button>
                @if($curhat->status !== 'ditangani')
                    <button type="button" id="handleBtn" class="rounded-full bg-[#DFF5E7] px-5 py-3 text-sm font-bold text-[#2E6B48] ring-1 ring-[#A3D9B8]">Tandai Selesai</button>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
        <h2 class="text-xl font-bold text-[#214D41]">Riwayat balasan</h2>
        <div id="balasanList" class="mt-5 space-y-4">
            @forelse($curhat->balasans as $balasan)
                <div class="rounded-2xl {{ $balasan->pengirim_id === auth()->id() ? 'bg-[#E7F2EC]' : 'bg-[#F7F4F0]' }} p-4">
                    <div class="flex items-center justify-between">
                        <p class="font-bold text-[#214D41]">{{ $balasan->pengirim->name ?? ($balasan->pengirim_role === 'siswa' ? 'Siswa' : 'Guru') }}</p>
                        <span class="text-xs uppercase tracking-[0.2em] text-[#6D7E79]">{{ $balasan->pengirim_role }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-[#214D41]">{{ $balasan->isi }}</p>
                        <span class="text-xs uppercase tracking-[0.2em] text-[#6D7E79]">{{ $balasan->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @empty
                <p id="emptyMsg" class="text-sm text-[#587067]">Belum ada balasan.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
document.getElementById('replyForm').addEventListener('submit', async function(e) {
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
            const emptyMsg = document.getElementById('emptyMsg');
            if (emptyMsg) emptyMsg.remove();
            const list = document.getElementById('balasanList');
            const div = document.createElement('div');
            div.className = 'rounded-2xl bg-[#E7F2EC] p-4';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <p class="font-bold text-[#214D41]">${data.balasan.pengirim_name}</p>
                    <span class="text-xs uppercase tracking-[0.2em] text-[#6D7E79]">${data.balasan.pengirim_role}</span>
                </div>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#3C504B]">${data.balasan.isi}</p>
                <p class="mt-2 text-[11px] text-[#6D7E79]">${data.balasan.created_at}</p>
            `;
            list.appendChild(div);
            textarea.value = '';
            list.scrollTop = list.scrollHeight;
        }
    } catch (err) {
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.textContent = 'Kirim balasan';
    }
});

const handleBtn = document.getElementById('handleBtn');
if (handleBtn) {
    handleBtn.addEventListener('click', async function() {
        if (!confirm('Tandai curhat ini sebagai selesai?')) return;
        this.disabled = true;
        this.textContent = 'Memproses...';
        try {
            const res = await fetch('{{ route("dashboard.handle", $curhat) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            if (data.success) {
                this.remove();
                const badge = document.querySelector('.inline-flex.rounded-full');
                if (badge) {
                    badge.className = 'inline-flex rounded-full px-3 py-1 text-xs font-bold bg-[#DFF5E7] text-[#2E6B48]';
                    badge.textContent = 'Ditangani';
                }
            }
        } catch (err) {
            console.error(err);
            this.disabled = false;
            this.textContent = 'Tandai Selesai';
        }
    });
}
</script>
@endsection
