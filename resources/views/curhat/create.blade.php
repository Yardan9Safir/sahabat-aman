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
                <label class="cursor-pointer rounded-2xl border border-[#D8D0C2] p-4 transition hover:border-[#5B7F71] hover:bg-[#F2F8F4] {{ old('tujuan') === 'teman' ? 'border-[#5B7F71] bg-[#F2F8F4]' : '' }}" onclick="updateTujuan('teman')">
                    <input type="radio" name="tujuan" value="teman" class="sr-only" {{ old('tujuan') === 'teman' || !old('tujuan') ? 'checked' : '' }}>
                    <div class="text-lg font-bold text-[#214D41]">👥 Teman Sebaya</div>
                    <div class="mt-1 text-sm text-[#587067]">Bercerita ke peer counselor</div>
                </label>
                <label class="cursor-pointer rounded-2xl border border-[#D8D0C2] p-4 transition hover:border-[#5B7F71] hover:bg-[#F2F8F4] {{ old('tujuan') === 'konselor' ? 'border-[#5B7F71] bg-[#F2F8F4]' : '' }}" onclick="updateTujuan('konselor')">
                    <input type="radio" name="tujuan" value="konselor" class="sr-only" {{ old('tujuan') === 'konselor' ? 'checked' : '' }}>
                    <div class="text-lg font-bold text-[#214D41]">👨‍🏫 Guru BK/Konselor</div>
                    <div class="mt-1 text-sm text-[#587067]">Butuh bantuan formal.</div>
                </label>
            </div>
            @error('tujuan')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama penerima - Teman Sebaya -->
        <div id="nama-teman-section" class="hidden">
            <label for="nama_penerima" class="mb-2 block text-sm font-semibold text-[#355C4F]">Nama teman sebaya (opsional)</label>
            <input type="text" id="nama_penerima" name="nama_penerima" class="w-full rounded-2xl border border-[#D8D0C2] bg-[#FBF9F5] px-4 py-3 text-sm focus:border-[#5B7F71] focus:outline-none" placeholder="Ketik nama teman yang ingin menerima curhat..." value="{{ old('nama_penerima') }}">
            <p class="mt-1 text-xs text-[#587067]">Jika kosong, akan dievaluasi oleh admin untuk disampaikan ke teman sebaya yang tepat.</p>
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

        <div id="anonim-container" class="flex items-center justify-between rounded-2xl bg-[#F5F2EA] p-4 transition">
            <div>
                <p class="text-sm font-semibold text-[#355C4F]">Kirim sebagai anonim</p>
                <p class="text-xs text-[#587067]" id="anonim-description">Identitasmu tidak ditampilkan ke penerima.</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
                <input type="checkbox" id="anonim-toggle" name="anonim" value="1" class="peer sr-only" checked>
                <div class="h-7 w-12 rounded-full bg-[#CFE0D8] transition peer-checked:bg-[#5B7F71] peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></div>
                <div class="absolute left-1 top-1 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></div>
            </label>
        </div>

        <button type="submit" class="w-full rounded-full bg-[#5B7F71] px-5 py-3 text-sm font-bold text-white shadow-md hover:bg-[#486A5E]">Kirim curhat</button>
    </form>
</div>

<script>
    function updateTujuan(value) {
        const namaPenerimaSectionTeman = document.getElementById('nama-teman-section');
        if (value === 'teman') {
            namaPenerimaSectionTeman.classList.remove('hidden');
        } else {
            namaPenerimaSectionTeman.classList.add('hidden');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const currentTujuan = document.querySelector('input[name="tujuan"]:checked')?.value;
        if (currentTujuan === 'teman') {
            document.getElementById('nama-teman-section').classList.remove('hidden');
        }

        // Handle nama_penerima input - disable anonim if name is filled
        const namaPenerimaInput = document.getElementById('nama_penerima');
        const anonimToggle = document.getElementById('anonim-toggle');
        const anonimContainer = document.getElementById('anonim-container');
        const anonimDescription = document.getElementById('anonim-description');

        function updateAnonimState() {
            if (!namaPenerimaInput || !anonimToggle) return;

            const hasName = namaPenerimaInput.value.trim() !== '';
            anonimToggle.disabled = hasName;

            if (hasName) {
                anonimToggle.checked = false;
                anonimContainer.classList.add('opacity-60');
                anonimDescription.textContent = 'Dinonaktifkan karena Anda memilih penerima tertentu.';
            } else {
                anonimContainer.classList.remove('opacity-60');
                anonimDescription.textContent = 'Identitasmu tidak ditampilkan ke penerima.';
                anonimToggle.checked = true;
            }
        }

        if (namaPenerimaInput) {
            namaPenerimaInput.addEventListener('input', updateAnonimState);
            // Check initial state on page load
            updateAnonimState();
        }

        // Add change event listener to category radio buttons for styling
        document.querySelectorAll('input[name="kategori"]').forEach(input => {
            input.addEventListener('change', function() {
                // Update all radio button label styles
                document.querySelectorAll('label[id^="kategori-label-"]').forEach(label => {
                    const radioInput = label.querySelector('input[name="kategori"]');
                    const radioDot = label.querySelector('div[class*="rounded-full"]');

                    if (radioInput.checked) {
                        label.classList.add('border-[#5B7F71]', 'bg-[#F2F8F4]');
                        label.classList.remove('border-[#D8D0C2]');
                        radioDot.classList.add('border-[#5B7F71]', 'bg-[#5B7F71]');
                        radioDot.classList.remove('border-[#D8D0C2]');
                        // Add inner circle
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
                        // Remove inner circle
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
