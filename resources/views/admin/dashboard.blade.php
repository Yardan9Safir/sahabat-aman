@extends('layouts.admin')

@section('title', 'Dashboard Admin - Sahabat Aman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 m-5">
        {{-- Statistik pengguna --}}
        <section class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/60 custom-shadow-resting">
            <div class="pb-3">
                <h1 class="text-headline-lg font-bold text-on-surface">Dashboard Admin</h1>
                <p class="text-body-md text-on-surface-variant mt-1">Ikhtisar Sistem Sahabat Aman: siswa, admin, guru, dan
                    konselor.</p>
            </div>
            <h2 class="text-title-md font-bold text-on-surface mb-4">Pengguna</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Total Pengguna</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['pengguna'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Siswa</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['siswa'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Guru BK</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['guru'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Konselor Sebaya</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['konselor'] }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40">
                    <p class="text-label-sm text-on-surface-variant">Admin</p>
                    <p class="text-headline-md font-bold text-on-surface">{{ $statistik['admin'] }}</p>
                </div>
            </div>
        </section>
    </div>
@endsection
