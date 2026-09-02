@extends('layouts.admin')

@section('title', 'Data Curhat - Sahabat Aman')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8A7B63]">Panel Admin</p>
        <h1 class="mt-1 text-3xl font-black text-[#214D41]">Data Curhat</h1>
        <p class="mt-1 text-sm text-[#587067]">Daftar seluruh curhat. Isi pesan tidak ditampilkan.</p>
    </div>

    <div class="rounded-[24px] bg-white p-4 shadow-sm ring-1 ring-[#E5DECF]">
        <form method="GET" class="grid gap-3 sm:grid-cols-3">
            <select name="kategori" class="rounded-xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm">
                <option value="">Semua kategori</option>
                <option value="pribadi" {{ request('kategori') === 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                <option value="sosial" {{ request('kategori') === 'sosial' ? 'selected' : '' }}>Sosial</option>
                <option value="belajar" {{ request('kategori') === 'belajar' ? 'selected' : '' }}>Belajar</option>
                <option value="karier" {{ request('kategori') === 'karier' ? 'selected' : '' }}>Karier</option>
            </select>
            <select name="status" class="rounded-xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm">
                <option value="">Semua status</option>
                <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                <option value="dilihat" {{ request('status') === 'dilihat' ? 'selected' : '' }}>Dilihat</option>
                <option value="dibalas" {{ request('status') === 'dibalas' ? 'selected' : '' }}>Dibalas</option>
                <option value="ditangani" {{ request('status') === 'ditangani' ? 'selected' : '' }}>Ditangani</option>
            </select>
            <button type="submit" class="rounded-xl bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white">Filter</button>
        </form>
    </div>

    @if($curhats->isEmpty())
        <div class="rounded-[24px] border border-dashed border-[#C8D5CD] bg-[#F9F7F3] p-10 text-center">
            <p class="text-lg font-bold text-[#214D41]">Belum ada curhat.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-[24px] bg-white shadow-sm ring-1 ring-[#E5DECF]">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E5DECF] bg-[#F8F5EF] text-xs uppercase tracking-[0.15em] text-[#8A7B63]">
                        <th class="px-5 py-3 font-semibold">Pengirim</th>
                        <th class="px-5 py-3 font-semibold">Penerima</th>
                        <th class="px-5 py-3 font-semibold">Kategori</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Waktu</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFE9DD]">
                    @foreach($curhats as $curhat)
                        <tr class="hover:bg-[#FAF8F3]">
                            <td class="px-5 py-4">
                                <span class="font-semibold text-[#214D41]">{{ $curhat->siswa->name ?? 'Tanpa pengirim' }}</span>
                                <span class="block text-xs text-[#8A7B63]">{{ $curhat->siswa ? ucfirst($curhat->siswa->role) : '' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-semibold text-[#214D41]">{{ $curhat->penerima->name ?? 'Tanpa penerima' }}</span>
                                <span class="block text-xs text-[#8A7B63]">{{ $curhat->penerima ? ucfirst($curhat->penerima->role) : '' }}</span>
                            </td>
                            <td class="px-5 py-4 capitalize text-[#355C4F]">{{ $curhat->kategori }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold {{ $curhat->status === 'terkirim' ? 'bg-[#EFE7FF] text-[#5B4A8E]' : ($curhat->status === 'dilihat' ? 'bg-[#E2F0FF] text-[#315B9C]' : ($curhat->status === 'dibalas' ? 'bg-[#FCE7D3] text-[#9A5A1B]' : 'bg-[#DFF5E7] text-[#2E6B48]')) }}">{{ ucfirst($curhat->status) }}</span>
                            </td>
                            <td class="px-5 py-4 text-[#587067]">{{ $curhat->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.show', $curhat) }}" class="rounded-full border border-[#B8C7BD] px-4 py-1.5 text-sm font-semibold text-[#355C4F]">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection