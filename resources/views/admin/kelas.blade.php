@extends('layouts.admin')

@section('title', 'Kelola Kelas Guru - Sahabat Aman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">Kelola Kelas Guru BK</h1>
            <p class="text-body-md text-on-surface-variant mt-1">
                Setiap guru BK hanya menerima curhat dari kelas yang diampunya.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($gurus as $guru)
            <section
                class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting overflow-hidden">
                <div class="p-5 border-b border-outline-variant/40 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-10 h-10 rounded-xl bg-secondary-container/60 text-on-secondary-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined">school</span>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-title-md font-bold text-on-surface truncate">{{ $guru->name }}</h2>
                            <p class="text-label-sm text-on-surface-variant">
                                {{ $guru->email }}
                                @if ($guru->identifier)
                                    &middot; {{ $guru->identifier }}
                                @endif
                            </p>
                        </div>
                        <span
                            class="px-3 py-1.5 rounded-full text-label-sm font-bold {{ count($guru->assignedKelas()) > 0 ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-surface-container-high text-on-surface-variant' }}">
                            {{ count($guru->assignedKelas()) }} kelas diampu
                        </span>
                    </div>
                </div>

                    <form method="POST" action="{{ route('admin.kelas.update', $guru) }}" class="p-5 space-y-4">
                        @csrf
                        @method('PUT')

                        @error('kelas')
                            <p class="text-xs text-error">{{ $message }}</p>
                        @enderror

                        @foreach ([7, 8, 9] as $tingkat)
                            <div>
                                <p class="text-label-md font-bold text-on-surface-variant mb-2">
                                    Kelas {{ $tingkat }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($semuaKelas as $kelas)
                                        @if (str_starts_with($kelas, (string) $tingkat))
                                            <label
                                                class="kelas-chip cursor-pointer select-none px-3 py-1.5 rounded-lg text-label-md border transition-colors {{ in_array($kelas, $guru->assignedKelas(), true) ? 'border-primary bg-surface-container text-primary font-bold' : 'border-outline-variant/60 bg-surface-container-lowest text-on-surface-variant hover:border-primary hover:text-primary' }}">
                                                <input type="checkbox" name="kelas[]" value="{{ $kelas }}"
                                                    class="sr-only peer"
                                                    {{ in_array($kelas, $guru->assignedKelas(), true) ? 'checked' : '' }}>
                                                <span>{{ $kelas }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    <div class="flex justify-end pt-1">
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all">
                            Simpan Kelas {{ $guru->name }}
                        </button>
                    </div>
                </form>
            </section>
        @empty
            <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting p-10 text-center">
                <span class="material-symbols-outlined text-outline text-4xl mb-2">group</span>
                <p class="text-body-md text-on-surface-variant">Belum ada akun guru BK.</p>
            </section>
        @endforelse
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.kelas-chip').forEach(chip => {
            const input = chip.querySelector('input');
            const sync = () => {
                if (input.checked) {
                    chip.classList.add('border-primary', 'bg-surface-container', 'text-primary', 'font-bold');
                    chip.classList.remove('border-outline-variant/60', 'bg-surface-container-lowest',
                        'text-on-surface-variant');
                } else {
                    chip.classList.remove('border-primary', 'bg-surface-container', 'text-primary',
                    'font-bold');
                    chip.classList.add('border-outline-variant/60', 'bg-surface-container-lowest',
                        'text-on-surface-variant');
                }
            };
            input.addEventListener('change', sync);
            sync();
        });
    </script>
@endsection
