@extends('layouts.admin')

@section('title', ($user->exists ? 'Edit' : 'Tambah') . ' Pengguna - Sahabat Aman')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <section
            class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant custom-shadow-resting flex flex-col gap-4 relative overflow-hidden">

            {{-- Title --}}
            <div>
                <h1 class="text-headline-lg font-bold text-on-surface">
                    {{ $user->exists ? 'Edit' : 'Tambah' }} Akun Pengguna
                </h1>

                <div class="mt-1 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-body-md text-on-surface-variant">
                        @if ($user->exists)
                            Ubah identitas <span class="font-bold text-on-surface">{{ $user->name }}</span>
                            ({{ $user->roleLabel() }}).
                        @else
                            Buat akun baru untuk siswa, guru, atau konselor sebaya.
                        @endif
                    </p>

                    <button type="button" onclick="window.location.href='{{ route('admin.users.index') }}'"
                        class="inline-flex items-center gap-1.5 text-label-md font-semibold bg-primary text-on-primary px-4 py-2 rounded-2xl transition-colors hover:bg-primary-container hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Kembali
                    </button>
                </div>
            </div>

            <form method="POST"
                action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
                class="custom-shadow-resting space-y-5">
                @csrf
                @if ($user->exists)
                    @method('PUT')
                @endif

                {{-- Data Diri --}}
                <section class="rounded-2xl border border-outline-variant/40 bg-surface-container-low/40 p-4 sm:p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-10 h-10 rounded-xl bg-secondary-container/60 text-on-secondary-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">badge</span>
                        </span>
                        <div>
                            <h2 class="text-title-md font-bold text-on-surface">Data Diri</h2>
                            <p class="text-body-sm text-on-surface-variant">Identitas dan kontak pengguna.</p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="name" class="text-label-lg font-bold text-on-surface mb-2 block">Nama
                                Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                required
                                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                            @error('name')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="identifier" class="text-label-lg font-bold text-on-surface mb-2 block">NIS / NIP /
                                ID</label>
                            <input type="text" name="identifier" id="identifier"
                                value="{{ old('identifier', $user->identifier) }}" required
                                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                            @error('identifier')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            required
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            placeholder="contoh@email.com">
                        @error('email')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- Peran & Kelas --}}
                <section class="rounded-2xl border border-outline-variant/40 bg-surface-container-low/40 p-4 sm:p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-10 h-10 rounded-xl bg-tertiary-container/60 text-on-tertiary-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">groups</span>
                        </span>
                        <div>
                            <h2 class="text-title-md font-bold text-on-surface">Peran &amp; Kelas</h2>
                            <p class="text-body-sm text-on-surface-variant">Tentukan peran akun ini.</p>
                        </div>
                    </div>

                    @php
                        $oldRole = old('role', $user->role ?? \App\Models\User::ROLE_SISWA);
                        $pilihanPeran = [
                            \App\Models\User::ROLE_SISWA => ['Siswa', 'Curhat &amp; baca quotes'],
                            \App\Models\User::ROLE_GURU => ['Guru BK', 'Terima dan tangani curhat'],
                            \App\Models\User::ROLE_KONSELOR_SEBAYA => [
                                'Konselor Sebaya',
                                'Dampingi teman sebaya (maks 10)',
                            ],
                        ];
                        if ($user->exists) {
                            $pilihanPeran[\App\Models\User::ROLE_ADMIN] = [
                                'Admin',
                                'Kelola pengguna &amp; pengaturan',
                            ];
                        }
                    @endphp

                    <div class="grid grid-cols-2 gap-3" id="role-cards">
                        @foreach ($pilihanPeran as $nilai => [$label, $deskripsi])
                            <label
                                class="role-card relative flex flex-col gap-1 p-4 rounded-2xl border-2 cursor-pointer select-none transition-all {{ $oldRole === $nilai ? 'border-primary bg-surface-container-low/60' : 'border-outline-variant/60 bg-surface-container-lowest hover:border-primary/50 hover:text-primary' }}">
                                <input type="radio" name="role" value="{{ $nilai }}" class="sr-only peer"
                                    required {{ $oldRole === $nilai ? 'checked' : '' }}>
                                <div class="flex items-center gap-2">
                                    <span class="text-label-lg font-bold text-on-surface">{{ $label }}</span>
                                </div>
                                <span
                                    class="text-body-sm text-on-surface-variant leading-snug">{!! $deskripsi !!}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="mt-2 text-xs text-error">{{ $message }}</p>
                    @enderror
                    <p class="mt-3 text-body-sm text-on-surface-variant">Maksimal 10 akun konselor sebaya</p>

                    <div id="kelas-field" class="mt-4">
                        <label for="kelas" class="text-label-lg font-bold text-on-surface mb-2 block">Kelas <span
                                class="font-normal text-outline">(khusus siswa)</span></label>
                        <select name="kelas" id="kelas"
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                            <option value="">-- Pilih kelas --</option>
                            @foreach (\App\Models\User::ALL_KELAS as $kelas)
                                <option value="{{ $kelas }}"
                                    {{ old('kelas', $user->kelas) === $kelas ? 'selected' : '' }}>{{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="guru-kelas-field" class="mt-4">
                        <p class="text-label-lg font-bold text-on-surface mb-2">Kelas Diampu <span
                                class="font-normal text-outline">(khusus guru — satu kelas hanya boleh satu guru)</span></p>
                        @php
                            $oldGuruKelas = old('guru_kelas');
                            $checkedGuruKelas = is_array($oldGuruKelas) ? $oldGuruKelas : $user->assignedKelas();
                        @endphp
                        @foreach ([7, 8, 9] as $tingkat)
                            <div class="mb-4 last:mb-0">
                                <p class="text-label-md font-bold text-on-surface-variant mb-2">
                                    Kelas {{ $tingkat }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (\App\Models\User::ALL_KELAS as $kelas)
                                        @if (str_starts_with($kelas, (string) $tingkat))
                                            <label
                                                class="kelas-chip cursor-pointer select-none px-3 py-1.5 rounded-lg text-label-md border transition-colors {{ in_array($kelas, $checkedGuruKelas, true) ? 'border-primary bg-surface-container text-primary font-bold' : 'border-outline-variant/60 bg-surface-container-lowest text-on-surface-variant hover:border-primary hover:text-primary' }}">
                                                <input type="checkbox" name="guru_kelas[]" value="{{ $kelas }}"
                                                    class="sr-only peer"
                                                    {{ in_array($kelas, $checkedGuruKelas, true) ? 'checked' : '' }}>
                                                <span>{{ $kelas }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        @error('guru_kelas')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- Password --}}
                <section class="rounded-2xl border border-outline-variant/40 bg-surface-container-low/40 p-4 sm:p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-10 h-10 rounded-xl bg-surface-container-high text-on-surface-variant flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">lock</span>
                        </span>
                        <div>
                            <h2 class="text-title-md font-bold text-on-surface">
                                {{ $user->exists ? 'Ganti Password' : 'Password' }}</h2>
                            <p class="text-body-sm text-on-surface-variant">
                                {{ $user->exists ? 'Kosongkan jika tidak diubah.' : 'Minimal 8 karakter.' }}</p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">
                                {{ $user->exists ? 'Password Baru' : 'Password' }}</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" minlength="8"
                                    {{ $user->exists ? '' : 'required' }}
                                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 pr-12 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                                    placeholder="Minimal 8 karakter">
                                <button type="button"
                                    class="pw-toggle absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl flex items-center justify-center text-outline hover:bg-surface-container hover:text-on-surface transition-colors"
                                    title="Tampilkan password">
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="text-label-lg font-bold text-on-surface mb-2 block">
                                {{ $user->exists ? 'Konfirmasi Password Baru' : 'Ulangi Password' }}</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    minlength="8" {{ $user->exists ? '' : 'required' }}
                                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 pr-12 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                                    placeholder="Ulangi password baru">
                                <button type="button"
                                    class="pw-toggle absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl flex items-center justify-center text-outline hover:bg-surface-container hover:text-on-surface transition-colors"
                                    title="Tampilkan password">
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Button Buat Akun & Batal --}}
                <div class="flex flex-wrap items-center justify-end gap-3 pt-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-7 py-3.5 rounded-2xl bg-primary text-on-primary text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">save</span>
                        {{ $user->exists ? 'Simpan Perubahan' : 'Buat Akun' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        const kelasField = document.getElementById('kelas-field');
        const kelasSelect = document.getElementById('kelas');
        const guruField = document.getElementById('guru-kelas-field');
        const roleCards = document.querySelectorAll('.role-card');

        const syncRoleCards = () => {
            roleCards.forEach(card => {
                const input = card.querySelector('input');
                card.classList.toggle('border-primary', input.checked);
                card.classList.toggle('bg-surface-container-low/60', input.checked);
                card.classList.toggle('border-outline-variant/60', !input.checked);
                card.classList.toggle('bg-surface-container-lowest', !input.checked);
                const dot = card.querySelector('.w-5.h-5');
                if (dot) {
                    dot.className = input.checked ?
                        'w-5 h-5 rounded-full flex items-center justify-center border-2 border-primary bg-primary text-white' :
                        'w-5 h-5 rounded-full flex items-center justify-center border border-outline';
                }
                const ikon = card.querySelector('.material-symbols-outlined.text-xl');
                if (ikon) ikon.classList.toggle('text-primary', input.checked);
            });
        };

        const toggleRoleFields = () => {
            const role = document.querySelector('input[name="role"]:checked')?.value;
            const butuhKelas = role === 'siswa';
            const isGuru = role === 'guru';
            kelasField.classList.toggle('hidden', !butuhKelas);
            kelasSelect.required = butuhKelas;
            guruField.classList.toggle('hidden', !isGuru);
            syncRoleCards();
        };

        roleCards.forEach(card => card.querySelector('input').addEventListener('change', toggleRoleFields));
        toggleRoleFields();

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

        document.querySelectorAll('.pw-toggle').forEach(btn => btn.addEventListener('click', () => {
            const input = btn.parentElement.querySelector('input');
            const tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            btn.querySelector('span').textContent = tampil ? 'visibility_off' : 'visibility';
        }));
    </script>
@endsection
