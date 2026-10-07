@extends('layouts.app')

@section('title', 'Daftar - Sahabat Aman')

@section('content')
<div class="mx-auto max-w-xl bg-surface-container-lowest p-7 rounded-3xl soft-shadow border border-surface-variant/40">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-2xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
            <span class="material-symbols-outlined">spa</span>
        </div>
        <div>
            <h1 class="text-headline-sm font-bold text-primary">Sahabat Aman</h1>
            <p class="text-label-sm text-outline">Ruang Aman Curhat Siswa</p>
        </div>
    </div>

    <h2 class="text-headline-sm font-bold text-on-surface mt-6">Buat Akun Baru</h2>
    <p class="mt-2 text-body-md text-on-surface-variant">Daftar sebagai siswa, guru, atau konselor sebaya.</p>

    @if(session('warning'))
    <div class="mt-4">
        @include('component.alert.failed', ['message' => session('warning')])
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="text-label-lg font-bold text-on-surface mb-2 block">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('name') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="identifier" class="text-label-lg font-bold text-on-surface mb-2 block">NIS / ID</label>
                <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                @error('identifier') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="text-label-lg font-bold text-on-surface mb-2 block">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                placeholder="contoh@email.com">
            @error('email') <p class="mt-2 text-xs text-error">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="password" class="text-label-lg font-bold text-on-surface mb-2 block">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="text-label-lg font-bold text-on-surface mb-2 block">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
            </div>
        </div>

        <div>
            <label for="role" class="text-label-lg font-bold text-on-surface mb-2 block">Peran</label>
            <select id="role" name="role"
                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                <option value="siswa">Siswa</option>
                <option value="guru">Guru</option>
                <option value="konselor sebaya">Konselor Sebaya</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div id="kelas-field">
            <label for="kelas" class="text-label-lg font-bold text-on-surface mb-2 block">Kelas <span
                    class="font-normal text-outline">(khusus siswa &amp; konselor sebaya)</span></label>
            <select id="kelas" name="kelas"
                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                <option value="">-- Pilih kelas --</option>
                @foreach (\App\Models\User::ALL_KELAS as $kelas)
                    <option value="{{ $kelas }}" {{ old('kelas') === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>
            @error('kelas')
                <p class="mt-2 text-xs text-error">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-body-sm text-on-surface-variant">Kelas menentukan guru BK dan konselor sebaya yang
                akan melayanimu. Tiap kelas hanya memiliki 2 konselor sebaya.</p>
        </div>

        @error('password')
            <p class="text-xs text-error">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full rounded-2xl bg-primary px-5 py-3.5 text-label-lg font-bold text-on-primary shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all">Daftar</button>
    </form>

    <script>
        const roleSelect = document.getElementById('role');
        const kelasField = document.getElementById('kelas-field');
        const kelasSelect = document.getElementById('kelas');
        const toggleKelas = () => {
            const role = roleSelect.value;
            const butuhKelas = role === 'siswa' || role === 'konselor sebaya';
            kelasField.classList.toggle('hidden', !butuhKelas);
            kelasSelect.required = butuhKelas;
        };
        roleSelect.addEventListener('change', toggleKelas);
        toggleKelas();
    </script>

    <p class="mt-5 text-center text-body-sm text-on-surface-variant">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Masuk</a>
    </p>
</div>
@endsection
