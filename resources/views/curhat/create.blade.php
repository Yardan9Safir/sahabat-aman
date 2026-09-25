@extends('layouts.app')

@section('title', 'Kirim Curhat - Sahabat Aman')

@section('content')
    <section
        class="bg-surface-container-lowest p-6 sm:p-8 rounded-3xl soft-shadow border border-surface-variant/40 gap-6 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary-fixed/20 rounded-full blur-3xl pointer-events-none">
        </div>
        
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-surface-variant/40">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-headline-sm font-bold text-on-surface">Mulai Curhat Baru</h3>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('curhat.store') }}" class="flex flex-col gap-6" id="venting-form">
            @csrf
            <div>
                @error('penerima_id')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
                @error('kategori')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
                @error('pesan')
                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 1. Pilih Target Pendengar --}}
            <div class="flex flex-col gap-2.5">
                <label class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-lg">group</span>
                    Pilih Siapa yang Ingin Mendengarmu:
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="target-listener-group">
                    <label
                        class="relative flex flex-col p-4 rounded-2xl border-2 {{ old('tujuan_tipe', 'guru') === 'guru' ? 'border-primary bg-surface-container-low/40' : 'border-outline-variant/60 bg-surface-container-lowest' }} cursor-pointer transition-all hover-lift">
                        <input type="radio" name="tujuan_tipe" value="guru" class="peer sr-only"
                            {{ old('tujuan_tipe', 'guru') === 'guru' ? 'checked' : '' }}>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2.5 py-1 rounded-full bg-secondary-container/60 text-on-secondary-container text-label-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">school</span> Guru BK
                            </span>
                            <div
                                class="w-5 h-5 rounded-full {{ old('tujuan_tipe', 'guru') === 'guru' ? 'border-2 border-primary bg-primary text-white' : 'border border-outline flex items-center justify-center text-transparent' }}">
                                <span class="material-symbols-outlined text-xs font-bold">check</span>
                            </div>
                        </div>
                    </label>
                    <label
                        class="relative flex flex-col p-4 rounded-2xl border-2 {{ old('tujuan_tipe') === 'konselor sebaya' ? 'border-primary bg-surface-container-low/40' : 'border-outline-variant/60 bg-surface-container-lowest' }} cursor-pointer transition-all hover-lift">
                        <input type="radio" name="tujuan_tipe" value="konselor sebaya" class="peer sr-only"
                            {{ old('tujuan_tipe') === 'konselor sebaya' ? 'checked' : '' }}>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2.5 py-1 rounded-full bg-tertiary-container/60 text-on-tertiary-container text-label-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">handshake</span> Konselor Sebaya
                            </span>
                            <div
                                class="w-5 h-5 rounded-full {{ old('tujuan_tipe') === 'konselor sebaya' ? 'border-2 border-primary bg-primary text-white' : 'border border-outline flex items-center justify-center text-transparent' }}">
                                <span class="material-symbols-outlined text-xs font-bold">check</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 2. Pilih Penerima --}}
            <div class="flex flex-col gap-2.5">
                <label class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-lg">person</span>
                    Pilih Penerima:
                </label>
                <select id="penerima_id" name="penerima_id" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    <option value="">-- Pilih penerima --</option>
                    <optgroup label="Guru" id="optgroup-guru">
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->id }}" data-tujuan="guru"
                                {{ old('tujuan_tipe', 'guru') === 'guru' && old('penerima_id') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Konselor Sebaya" id="optgroup-konselor">
                        @foreach ($konselors as $konselor)
                            <option value="{{ $konselor->id }}" data-tujuan="konselor sebaya"
                                {{ old('tujuan_tipe') === 'konselor sebaya' && old('penerima_id') == $konselor->id ? 'selected' : '' }}>
                                {{ $konselor->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            {{-- 3. Topik Pembahasan --}}
            <div class="flex flex-col gap-2.5">
                <label class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-lg">category</span>
                    Topik Pembahasan:
                </label>
                <div class="flex flex-wrap gap-2" id="category-tags">
                    @foreach (['belajar' => ['Belajar', 'menu_book'], 'sosial' => ['Sosial', 'group'], 'pribadi' => ['Pribadi', 'psychology'], 'karier' => ['Karier', 'self_improvement']] as $key => [$label, $icon])
                        <button
                            class="category-tag px-3.5 py-2 rounded-xl text-label-md border-2 {{ old('kategori') === $key ? 'border-primary bg-surface-container text-primary font-bold' : 'border-outline-variant/60 bg-surface-container-lowest text-on-surface-variant hover:border-primary hover:text-primary' }} flex items-center gap-1.5 transition-colors"
                            type="button" data-value="{{ $key }}">
                            <span class="material-symbols-outlined text-base">{{ $icon }}</span>
                            {{ $label }}
                        </button>
                    @endforeach
                    <input type="hidden" name="kategori" id="kategori-input" value="{{ old('kategori') }}">
                </div>
            </div>

            {{-- 4. Textarea --}}
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <label class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">draw</span>
                        Ruang Luapan Pikiranmu:
                    </label>
                    <span class="text-label-sm text-outline" id="char-counter">0 / 2000 karakter</span>
                </div>
                <textarea
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 sm:p-5 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all resize-y outline-none"
                    id="curhat-text" name="pesan" rows="6"
                    placeholder="Tuliskan apa saja yang kamu rasakan, tidak ada penilaian di sini... Ceritakan beban ujian, rasa lelah, kesal, atau hal yang mengganjal hatimu.">{{ old('pesan') }}</textarea>
            </div>

            {{-- 5. Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ url()->previous() }}"
                        class="flex-1 sm:flex-none px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors text-center">
                        Batal
                    </a>
                    <button
                        class="flex-1 sm:flex-none px-4 py-3 rounded-2xl bg-primary text-white text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2"
                        type="submit">
                        <span class="material-symbols-outlined text-md">send</span> Kirim Curhat
                    </button>
                </div>
            </div>
        </form>
    </section>

    <section
        class="bg-linear-to-br from-secondary-fixed/50 to-surface-container-low p-6 rounded-3xl border border-secondary-fixed/40 soft-shadow">
        <div class="flex items-center gap-2 text-secondary mb-3">
            <span class="material-symbols-outlined text-2xl">lightbulb</span>
            <span class="text-label-md font-bold uppercase tracking-wider">Tips Sebelum Curhat</span>
        </div>
        <ul class="space-y-2 text-body-md text-on-surface-variant">
            <li class="flex items-start gap-2"><span
                    class="material-symbols-outlined text-primary text-sm mt-1">check</span> Tulis dengan jujur, tidak ada
                jawaban yang salah di sini.</li>
            <li class="flex items-start gap-2"><span
                    class="material-symbols-outlined text-primary text-sm mt-1">check</span> Cerita yang dikirim tidak akan
                dibagikan ke pihak lain tanpa izinmu.</li>
        </ul>
    </section>
@endsection

@section('scripts')
    <script>
        const textarea = document.getElementById('curhat-text');
        const counter = document.getElementById('char-counter');
        const kategoriInput = document.getElementById('kategori-input');

        textarea.addEventListener('input', (e) => {
            counter.textContent = `${e.target.value.length} / 2000 karakter`;
        });

        const categoryTags = document.querySelectorAll('.category-tag');
        categoryTags.forEach(tag => {
            tag.addEventListener('click', () => {
                categoryTags.forEach(t => {
                    t.classList.remove('border-2', 'border-primary', 'bg-surface-container',
                        'text-primary', 'font-bold');
                    t.classList.add('border', 'border-outline-variant/60',
                        'bg-surface-container-lowest', 'text-on-surface-variant');
                });
                tag.classList.add('border-2', 'border-primary', 'bg-surface-container', 'text-primary',
                    'font-bold');
                tag.classList.remove('border', 'border-outline-variant/60', 'bg-surface-container-lowest',
                    'text-on-surface-variant');
                kategoriInput.value = tag.dataset.value;
            });
        });

        const listenerCards = document.querySelectorAll('#target-listener-group label');
        listenerCards.forEach(card => {
            card.addEventListener('click', () => {
                const value = card.querySelector('input').value;
                listenerCards.forEach(c => {
                    c.classList.remove('border-2', 'border-primary', 'bg-surface-container-low/40');
                    c.classList.add('border', 'border-outline-variant/60',
                        'bg-surface-container-lowest');
                    const indicator = c.querySelector('.w-5.h-5');
                    if (indicator) {
                        indicator.className =
                            "w-5 h-5 rounded-full border border-outline flex items-center justify-center text-transparent";
                    }
                });
                card.classList.add('border-2', 'border-primary', 'bg-surface-container-low/40');
                card.classList.remove('border', 'border-outline-variant/60', 'bg-surface-container-lowest');
                const indicator = card.querySelector('.w-5.h-5');
                if (indicator) {
                    indicator.className =
                        "w-5 h-5 rounded-full border-2 border-primary bg-primary flex items-center justify-center text-white";
                }
                const select = document.getElementById('penerima_id');
                Array.from(select.options).forEach(option => {
                    if (option.value) {
                        option.style.display = option.dataset.tujuan === value ? '' : 'none';
                    }
                });
                if (select.value && select.selectedOptions[0]?.dataset?.tujuan !== value) {
                    select.value = '';
                }
            });
        });

        const selectedTujuan = document.querySelector('input[name="tujuan_tipe"]:checked')?.value || 'guru';
        const select = document.getElementById('penerima_id');
        Array.from(select.options).forEach(option => {
            if (option.value) {
                option.style.display = option.dataset.tujuan === selectedTujuan ? '' : 'none';
            }
        });
    </script>
@endsection
