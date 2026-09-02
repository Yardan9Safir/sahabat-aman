@extends('layouts.admin')

@section('title', 'Data Siswa - Sahabat Aman')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8A7B63]">Panel Admin</p>
        <h1 class="mt-1 text-3xl font-black text-[#214D41]">Data Siswa</h1>
        <p class="mt-1 text-sm text-[#587067]">Seluruh siswa yang terdaftar di Sahabat Aman.</p>
    </div>

    <div class="flex items-center justify-between">
        <p class="text-sm font-semibold text-[#355C4F]">{{ $siswas->count() }} siswa terdaftar</p>
    </div>

    @if($siswas->isEmpty())
        <div class="rounded-3xl border border-dashed border-[#C8D5CD] bg-[#F9F7F3] p-10 text-center">
            <p class="text-lg font-bold text-[#214D41]">Belum ada siswa terdaftar.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-[#E5DECF]">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#E5DECF] bg-[#F8F5EF] text-xs uppercase tracking-[0.15em] text-[#8A7B63]">
                        <th class="px-5 py-3 font-semibold">Nama</th>
                        <th class="px-5 py-3 font-semibold">Identitas</th>
                        <th class="px-5 py-3 font-semibold">Email</th>
                        <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFE9DD]">
                    @foreach($siswas as $siswa)
                        <tr class="hover:bg-[#FAF8F3]">
                            <td class="px-5 py-4 font-semibold text-[#214D41]">{{ $siswa->name }}</td>
                            <td class="px-5 py-4 text-[#355C4F]">{{ $siswa->identifier }}</td>
                            <td class="px-5 py-4 text-[#587067]">{{ $siswa->email }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="rounded-full bg-[#E2F0FF] px-3 py-1.5 text-xs font-bold text-[#315B9C] hover:bg-[#C9E0FA]">Edit</a>
                                    <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" onsubmit="return confirm('Hapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-[#1a1717] px-3 py-1.5 text-xs font-bold text-[#c71d1d] hover:bg-[#c37c7c]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection