@extends('layouts.app')

@section('title', 'Server Sedang Penuh - Sahabat Aman')

@section('content')
    <div class="max-w-md mx-auto bg-surface-container-lowest p-8 rounded-3xl soft-shadow border border-surface-variant/40 text-center">
        <div class="w-16 h-16 rounded-2xl bg-error-container text-on-error-container flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">signal_wifi_connected_no_internet_4</span>
        </div>
        <h1 class="text-headline-sm font-bold text-on-surface">Server Sedang Penuh</h1>
        <p class="text-body-md text-on-surface-variant mt-2">
            Terlalu banyak permintaan masuk dalam waktu singkat. Silakan tunggu beberapa saat lalu coba lagi.
        </p>
        <a href="{{ route('welcome') }}"
            class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-on-primary font-label-lg font-bold shadow-md hover:bg-primary-container transition-all">
            <span class="material-symbols-outlined text-lg">home</span> Kembali ke Beranda
        </a>
    </div>
@endsection