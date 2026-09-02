@extends('layouts.app')

@section('title', 'Detail Curhat')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">Detail curhat</p>
                    <h1 class="mt-2 text-3xl font-black text-[#214D41]">{{ ucfirst($curhat->kategori) }}</h1>
                </div>
                <span
                    class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
            </div>

            <div class="mt-6 grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl bg-[#F5F2EA] p-4">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Penerima</p>
                    <p class="mt-2 font-bold text-[#214D41]">{{ $curhat->penerima->name ?? '-' }} -
                        {{ $curhat->penerima ? ucfirst($curhat->penerima->role) : '' }}</p>
                </div>
                <div class="rounded-2xl bg-[#F5F2EA] p-4">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-[#6D7E79]">Waktu</p>
                    <p class="mt-2 font-bold text-[#214D41]">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div class="rounded-2xl bg-[#F5F2EA] p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#6D7E79]">Pesan asli</p>
                    <p class="mt-3 whitespace-pre-line font-bold leading-7 text-[#3C504B]">{{ $curhat->pesan }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
            <h2 class="text-xl font-bold text-[#214D41]">Timeline</h2>
            <div class="mt-5 space-y-4">
                @foreach (['terkirim', 'dilihat', 'dibalas', 'ditangani'] as $status)
                    @php $active = array_search($curhat->status, ['terkirim','dilihat','dibalas','ditangani']) >= array_search($status, ['terkirim','dilihat','dibalas','ditangani']); @endphp
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full {{ $active ? 'bg-[#5B7F71] text-white' : 'bg-[#EDE7DB] text-[#7C8E84]' }}">
                            {{ $loop->index + 1 }}
                        </div>
                        <div class="flex-1 rounded-2xl {{ $active ? 'bg-[#F2F8F4]' : 'bg-[#F7F4F0]' }} p-3">
                            <p class="text-sm font-bold text-[#214D41]">{{ ucfirst($status) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
            <h2 class="text-xl font-bold text-[#214D41]">Balas curhat</h2>
            <form id="replyForm" class="mt-5 space-y-4">
                @csrf
                <textarea name="isi" rows="5" required
                    class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none"
                    placeholder="Tulis balasan..."></textarea>
                <button type="submit" id="replyBtn"
                    class="rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white">Kirim balasan</button>
            </form>
        </div>

        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-[#E5DECF]">
            <h2 class="text-xl font-bold text-[#214D41]">Balasan</h2>
            <div id="balasanList" class="mt-5 space-y-4">
                @forelse($curhat->balasans as $balasan)
                    <div
                        class="rounded-2xl {{ $balasan->pengirim_id === auth()->id() ? 'bg-[#E7F2EC]' : 'bg-[#F7F4F0]' }} p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-[#214D41]">
                                {{ $balasan->pengirim->name ?? ($balasan->pengirim_role === 'siswa' ? 'Siswa' : 'Guru') }}
                            </p>
                            <span
                                class="text-xs uppercase tracking-[0.2em] text-[#6D7E79]">{{ $balasan->pengirim_role }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#3C504B]">{{ $balasan->isi }}</p>
                            <p class="mt-2 text-[11px] text-[#6D7E79]">
                                {{ $balasan->created_at->translatedFormat('d M Y, H:i') }}</p>
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
                const res = await fetch('{{ route('dashboard.reply', $curhat) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        isi
                    }),
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
    </script>
@endsection
