@extends('layouts.admin')

@section('title', 'Edit Pengguna - Sahabat Aman')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-headline-lg font-bold text-on-surface">Edit Pengguna</h1>
                <p class="text-body-md text-on-surface-variant mt-1">
                    Ubah identitas <span class="font-bold text-on-surface">{{ $user->name }}</span>
                    ({{ $user->roleLabel() }}).
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-1.5 text-label-md font-semibold text-primary hover:underline">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kembali ke Kelola Pengguna
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}"
            class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting p-5 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Data Diri --}}
            <div>
                <h2 class="text-title-md font-bold text-on-surface mb-4">Data Diri</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="text-label-lg font-bold text-on-surface mb-2 block">Nama
                            Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
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

                <div class="mt-5">
                    <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                        placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-2 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Peran & Kelas --}}
            <div class="border-t border-outline-variant/40 pt-5">
                <div>
                    <label for="role" class="text-label-lg font-bold text-on-surface mb-2 block">Peran</label>
                    <select name="role" id="role" required
                        class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        <option value="{{ \App\Models\User::ROLE_SISWA }}"
                            {{ old('role', $user->role) === \App\Models\User::ROLE_SISWA ? 'selected' : '' }}>Siswa
                        </option>
                        <option value="{{ \App\Models\User::ROLE_GURU }}"
                            {{ old('role', $user->role) === \App\Models\User::ROLE_GURU ? 'selected' : '' }}>Guru
                        </option>
                        <option value="{{ \App\Models\User::ROLE_KONSELOR_SEBAYA }}"
                            {{ old('role', $user->role) === \App\Models\User::ROLE_KONSELOR_SEBAYA ? 'selected' : '' }}>
                            Konselor Sebaya</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}"
                            {{ old('role', $user->role) === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>Admin
                        </option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div id="kelas-field" class="mt-5">
                    <label for="kelas" class="text-label-lg font-bold text-on-surface mb-2 block">Kelas <span
                            class="font-normal text-outline">(khusus siswa &amp; konselor sebaya)</span></label>
                    <select name="kelas" id="kelas"
                        class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        <option value="">-- Pilih kelas --</option>
                        @foreach (\App\Models\User::ALL_KELAS as $kelas)
                            <option value="{{ $kelas }}"
                                {{ old('kelas', $user->kelas) === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    </select>
                    @error('kelas')
                        <p class="mt-2 text-xs text-error">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-body-sm text-on-surface-variant">Untuk konselor sebaya: tiap kelas hanya
                        boleh memiliki 2 konselor.</p>
                </div>

                <div id="guru-kelas-field" class="mt-5">
                    <p class="text-label-lg font-bold text-on-surface mb-2">Kelas Diampu <span
                            class="font-normal text-outline">(khusus guru)</span></p>
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
                    <p class="mt-2 text-body-sm text-on-surface-variant">Curhat siswa hanya diteruskan ke guru BK yang
                        mengampu kelasmu.</p>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="border-t border-outline-variant/40 pt-5">
                <h2 class="text-title-md font-bold text-on-surface mb-1">Ganti Password</h2>
                <p class="text-body-sm text-on-surface-variant mb-4">Kosongkan jika tidak diubah.</p>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">Password
                            Baru</label>
                        <input type="password" name="password" id="password" minlength="8"
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-2 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="text-label-lg font-bold text-on-surface mb-2 block">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                            class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-outline-variant/40">
                <a href="{{ route('admin.users.index') }}"
                    class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-7 py-3.5 rounded-2xl bg-primary text-on-primary text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const kelasField = document.getElementById('kelas-field');
        const kelasSelect = document.getElementById('kelas');
        const guruField = document.getElementById('guru-kelas-field');

        const toggleRoleFields = () => {
            const role = roleSelect.value;
            const butuhKelas = role === 'siswa' || role === 'konselor sebaya';
            const isGuru = role === 'guru';
            kelasField.classList.toggle('hidden', !butuhKelas);
            kelasSelect.required = butuhKelas;
            guruField.classList.toggle('hidden', !isGuru);
        };
        roleSelect.addEventListener('change', toggleRoleFields);
        toggleRoleFields();

        document.querySelectorAll('.kelas-chip').forEach(chip => {
            const input = chip.querySelector('input');
            const sync = () => {
                if (input.checked) {
                    chip.classList.add('border-primary', 'bg-surface-container', 'text-primary', 'font-bold');
                    chip.classList.remove('border-outline-variant/60', 'bg-surface-container-lowest', 'text-on-surface-variant');
                } else {
                    chip.classList.remove('border-primary', 'bg-surface-container', 'text-primary', 'font-bold');
                    chip.classList.add('border-outline-variant/60', 'bg-surface-container-lowest', 'text-on-surface-variant');
                }
            };
            input.addEventListener('change', sync);
            sync();
        });
    </script>
@endsection
