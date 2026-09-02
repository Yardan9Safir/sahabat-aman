@extends('layouts.app')

@section('title', 'Kirim Curhat - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-3xl rounded-[30px] bg-white p-6 shadow-[0_18px_45px_rgba(69,102,89,0.08)] ring-1 ring-[#E5DECF] sm:p-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#5B7F71]">Curhat baru</p>
            <h1 class="mt-2 text-3xl font-black text-[#214D41]">Kirim curhat</h1>
        </div>
        <a href="{{ route('riwayat') }}" class="rounded-full border border-[#B8C7BD] px-4 py-2 text-sm font-medium text-[#355C4F]">Riwayat</a>
    </div>

    <form method="POST" action="{{ route('curhat.store') }}" class="space-y-6">
        @csrf

        <div>
            <p class="mb-3 text-sm font-semibold text-[#355C4F]">Tujuan curhat</p>
            <div class="grid gap-3 sm:grid-cols-2">
                <label class="cursor-pointer rounded-2xl border border-[#D8D0C2] p-4 transition hover:border-[#5B7F71] hover:bg-[#F2F8F4] {{ old('tujuan') !== 'siswa' ? 'border-[#5B7F71] bg-[#F2F8F4]' : '' }}" onclick="toggleTujuan('guru')">
                    <input type="radio" name="tujuan_tipe" value="guru" id="tujuan-guru" class="sr-only" {{ old('tujuan') !== 'siswa' ? 'checked' : '' }}>
                    <div class="text-lg font-bold text-[#214D41]">👨‍🏫 Guru</div>
                    <div class="mt-1 text-sm text-[#587067]">Bercerita ke guru</div>
                </label>
                <label class="cursor-pointer rounded-2xl border border-[#D8D0C2] p-4 transition hover:border-[#5B7F71] hover:bg-[#F2F8F4] {{ old('tujuan') === 'siswa' ? 'border-[#5B7F71] bg-[#F2F8F4]' : '' }}" onclick="toggleTujuan('siswa')">
                    <input type="radio" name="tujuan_tipe" value="siswa" id="tujuan-siswa" class="sr-only" {{ old('tujuan') === 'siswa' ? 'checked' : '' }}>
                    <div class="text-lg font-bold text-[#214D41]">👥 Siswa Lain</div>
                    <div class="mt-1 text-sm text-[#587067]">Curhat ke teman sekelas</div>
                </label>
            </div>
            @error('penerima_id')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pilih penerima -->
        <div>
            <label for="penerima_id" class="mb-2 block text-sm font-semibold text-[#355C4F]">Pilih penerima</label>
            <select id="penerima_id" name="penerima_id" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none">
                <option value="">-- Pilih penerima --</option>
                <optgroup label="Guru" id="optgroup-guru">
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" data-tujuan="guru" {{ old('tujuan') === 'guru' && old('penerima_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }} ({{ $guru->identifier }})</option>
                    @endforeach
                </optgroup>
                <optgroup label="Siswa" id="optgroup-siswa">
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}" data-tujuan="siswa" {{ old('tujuan') === 'siswa' && old('penerima_id') == $siswa->id ? 'selected' : '' }}>{{ $siswa->name }} ({{ $siswa->identifier }})</option>
                    @endforeach
                </optgroup>
            </select>
            <p class="mt-1 text-xs text-[#587067]">Pilih guru atau siswa yang akan menerima curhatmu.</p>
        </div>

        <div>
            <p class="mb-3 text-sm font-semibold text-[#355C4F]">Kategori masalah</p>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach(['pribadi'=>'Pribadi','sosial'=>'Sosial','belajar'=>'Belajar','karier'=>'Karier'] as $key => $label)
                    <label class="cursor-pointer rounded-2xl border-2 border-[#D8D0C2] p-4 transition hover:border-[#5B7F71] hover:bg-[#F2F8F4] {{ old('kategori') === $key ? 'border-[#5B7F71] bg-[#F2F8F4]' : '' }} flex items-center gap-3 justify-center kategori-label-{{ $key }}" id="kategori-label-{{ $key }}">
                        <input type="radio" name="kategori" value="{{ $key }}" class="sr-only" id="kategori-{{ $key }}" {{ old('kategori') === $key ? 'checked' : '' }}>
                        <div class="relative w-5 h-5 rounded-full border-2 {{ old('kategori') === $key ? 'border-[#5B7F71] bg-[#5B7F71]' : 'border-[#D8D0C2]' }} transition-all flex items-center justify-center shrink-0">
                            @if(old('kategori') === $key)
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </div>
                        <div class="text-sm font-bold text-[#214D41]">{{ $label }}</div>
                    </label>
                @endforeach
            </div>
            @error('kategori')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="pesan" class="mb-2 block text-sm font-semibold text-[#355C4F]">Isi curhat</label>
            <textarea id="pesan" name="pesan" rows="6" required class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none" placeholder="Ceritakan keadaanmu dengan jujur dan aman...">{{ old('pesan') }}</textarea>
            @error('pesan')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white shadow-md hover:bg-[#486A5E]">Kirim curhat</button>
    </form>
</div>

<script>
    function toggleTujuan(tipe) {
        const select = document.getElementById('penerima_id');

        Array.from(select.options).forEach(option => {
            option.disabled = option.dataset.tujuan !== tipe;
        });

        if (select.value && select.selectedOptions.length && select.selectedOptions[0].dataset.tujuan !== tipe) {
            select.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectedTujuan = document.querySelector('input[name="tujuan_tipe"]:checked')?.value || 'guru';
        toggleTujuan(selectedTujuan);

        document.querySelectorAll('input[name="kategori"]').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('label[id^="kategori-label-"]').forEach(label => {
                    const radioInput = label.querySelector('input[name="kategori"]');
                    const radioDot = label.querySelector('div[class*="rounded-full"]');

                    if (radioInput.checked) {
                        label.classList.add('border-[#5B7F71]', 'bg-[#F2F8F4]');
                        label.classList.remove('border-[#D8D0C2]');
                        radioDot.classList.add('border-[#5B7F71]', 'bg-[#5B7F71]');
                        radioDot.classList.remove('border-[#D8D0C2]');
                        if (!radioDot.querySelector('div')) {
                            const innerCircle = document.createElement('div');
                            innerCircle.className = 'w-2 h-2 bg-white rounded-full';
                            radioDot.appendChild(innerCircle);
                        }
                    } else {
                        label.classList.remove('border-[#5B7F71]', 'bg-[#F2F8F4]');
                        label.classList.add('border-[#D8D0C2]');
                        radioDot.classList.remove('border-[#5B7F71]', 'bg-[#5B7F71]');
                        radioDot.classList.add('border-[#D8D0C2]');
                        const innerCircle = radioDot.querySelector('div');
                        if (innerCircle) {
                            innerCircle.remove();
                        }
                    }
                });
            });
        });
    });
</script>
@endsection
