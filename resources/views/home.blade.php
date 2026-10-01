<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Aman &mdash; Layanan Bimbingan &amp; Konseling Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        .tactile-card {
            box-shadow: 0 4px 20px -2px rgba(120, 113, 108, .06), 0 2px 6px -1px rgba(2, 132, 199, .04);
            transition: transform .2s cubic-bezier(.16, 1, .3, 1), box-shadow .2s cubic-bezier(.16, 1, .3, 1);
        }

        .tactile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -4px rgba(120, 113, 108, .12), 0 4px 12px -2px rgba(245, 158, 11, .06);
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body
    class="bg-background font-sans text-on-surface min-h-screen flex flex-col antialiased selection:bg-primary-fixed selection:text-on-primary-fixed"
    data-swal-flash-success="{{ session('success') }}" data-swal-flash-info="{{ session('info') }}">

    @php
        $isGuru = auth()->check() && auth()->user()->isGuru();
        $primaryAction = auth()->check() ? route('dashboard') : route('login');
        $primaryLabel = auth()->check() ? 'Buka Kotak Masuk' : 'Mulai Curhat';
        $hotline = config('services.hotline.phone');
        $accent = [
            ['bar' => 'bg-primary', 'tile' => 'bg-primary-fixed text-primary', 'name' => 'text-primary'],
            [
                'bar' => 'bg-secondary-container',
                'tile' => 'bg-secondary-fixed text-secondary',
                'name' => 'text-secondary',
            ],
            ['bar' => 'bg-tertiary', 'tile' => 'bg-tertiary-fixed text-tertiary', 'name' => 'text-tertiary'],
        ];
    @endphp

    {{-- Top nav --}}
    <header
        class="sticky top-0 z-50 bg-background/90 backdrop-blur-md border-b border-outline-variant/30 shadow-sm">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-6 h-20 w-full">
            <a class="flex items-center gap-2.5 shrink-0" href="{{ route('welcome') }}">
                <div class="w-10 h-10 rounded-xl bg-primary-fixed text-primary flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl"
                        style="font-variation-settings: 'FILL' 1;">spa</span>
                </div>
                <span class="text-lg font-bold text-primary tracking-tight">Sahabat Aman</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ $primaryAction }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary text-sm shadow-sm transition-all duration-150 active:scale-95">
                    <span class="material-symbols-outlined text-lg">edit_note</span>
                    <span>{{ $primaryLabel }}</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 flex flex-col w-full">

        {{-- 1. Hero --}}
        <section id="beranda" class="relative pt-12 pb-16 md:pt-20 md:pb-24 overflow-hidden">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-secondary-fixed/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-4xl mx-auto px-6 text-center relative">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-fixed text-on-secondary-fixed border border-secondary-fixed-dim/40 mb-6">
                    <span class="material-symbols-outlined text-secondary text-base"
                        style="font-variation-settings: 'FILL' 1;">lock</span>
                    <span class="text-xs font-semibold">100% Bebas Stigma &bull; Kerahasiaan Terjamin</span>
                </div>

                <h1
                    class="text-[34px] md:text-[48px] leading-[44px] md:leading-[56px] font-extrabold text-on-surface tracking-tight mb-6 text-balance">
                    Setiap Cerita Berhak Didengar oleh
                    <span
                        class="text-primary underline decoration-secondary-container decoration-wavy underline-offset-8">Sahabat Aman</span>
                </h1>

                <p class="text-base md:text-lg text-on-surface-variant max-w-2xl mx-auto mb-10 text-balance leading-relaxed">
                    Bimbingan konseling sekolah yang hangat dan aman tanpa rasa takut atau dihakimi. Suarakan isi
                    hatimu, bersama kita temukan ketenangan.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ $primaryAction }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary text-lg shadow-md hover:shadow-lg transition-all active:scale-95">
                        <span class="material-symbols-outlined text-xl">chat_bubble</span>
                        <span>{{ $primaryLabel }}</span>
                    </a>
                </div>

                <div
                    class="mt-12 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs font-semibold text-on-surface-variant">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">verified_user</span>
                        <span>Konselor Berpengalaman</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-lg"
                            style="font-variation-settings: 'FILL' 1;">favorite</span>
                        <span>Ruang Tanpa Penghakiman</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">visibility_off</span>
                        <span>Privasi Siswa 100% Aman</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- 2. Quotes --}}
        <section id="quotes" class="py-12 md:py-20 bg-surface-container-low border-t border-b border-surface-container">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-14">
                    <span
                        class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-xs font-semibold uppercase tracking-wider mb-3">
                        <span class="material-symbols-outlined text-sm">auto_awesome</span>
                        Kata-Kata Hari Ini
                    </span>
                    <h2 class="text-2xl md:text-[32px] md:leading-[40px] font-bold text-on-surface">
                        Inspirasi &amp; Penguat Langkahmu Hari Ini
                    </h2>
                    <p class="text-base text-on-surface-variant mt-2">
                        Kalimat penuh kehangatan dari para pendamping sekolah untuk menemani harimu agar terasa lebih
                        ringan.
                    </p>
                </div>

                @if ($quotes->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">
                        @foreach ($quotes as $index => $quote)
                            @php $warna = $accent[$index % count($accent)]; @endphp
                            <article
                                class="tactile-card relative bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-7 flex flex-col justify-between overflow-hidden">
                                <div class="absolute top-0 left-0 right-0 h-1.5 {{ $warna['bar'] }}"></div>

                                <div>
                                    <div class="flex items-center justify-between mb-5 gap-3">
                                        <span
                                            class="px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-xs font-medium truncate">
                                            {{ $quote->displayAuthorContext() }}
                                        </span>
                                    </div>

                                    <blockquote
                                        class="text-lg font-semibold text-on-surface leading-relaxed mb-6">
                                        &ldquo;{{ $quote->isi_quotes }}&rdquo;
                                    </blockquote>
                                </div>

                                <div class="pt-4 border-t border-surface-container flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold {{ $warna['name'] }} truncate">
                                            {{ $quote->displayAuthor() }}
                                        </p>
                                        <p class="text-xs text-outline">{{ $quote->displayAuthorRole() }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button type="button" data-like-quote title="Sukai Kutipan"
                                            class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error-container/30 transition-colors">
                                            <span class="material-symbols-outlined text-xl">favorite_border</span>
                                        </button>
                                        <button type="button" data-copy-quote
                                            title="Salin Kutipan"
                                            data-copy-quote="{{ $quote->isi_quotes }} &mdash; {{ $quote->displayAuthor() }}"
                                            class="p-2 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-xl">content_copy</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div
                        class="bg-surface-container-lowest rounded-2xl border border-dashed border-outline-variant/50 p-12 text-center">
                        <span class="material-symbols-outlined text-outline text-4xl mb-3">format_quote</span>
                        <p class="text-lg font-bold text-on-surface">Quotes akan hadir di sini</p>
                        <p class="text-sm text-on-surface-variant mt-1">
                            Belum ada quotes. Guru BK, konselor sebaya, dan siswa bisa mulai mengisinya.
                        </p>
                    </div>
                @endif
            </div>
        </section>

        {{-- 3. Kerahasiaan & hotline --}}
        <section id="kerahasiaan" class="py-12 md:py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/40 p-8 md:p-12 tactile-card">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-4">
                            <div
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-xs font-semibold">
                                <span class="material-symbols-outlined text-base">shield</span>
                                <span>Standar Etika Konseling Sekolah</span>
                            </div>
                            <h3 class="text-2xl font-bold text-on-surface">
                                Kerahasiaanmu adalah Prioritas Utama Kami
                            </h3>
                            <p class="text-base text-on-surface-variant leading-relaxed">
                                Seluruh catatan dan obrolan berada di bawah sumpah profesi konselor. Guru BK dan konselor
                                sebaya tidak akan membagikan ceritamu ke pihak lain tanpa persetujuanmu. Ruang ini adalah
                                rumah amanmu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-surface-container-low border-t border-outline-variant/30 text-on-surface-variant">
        <div
            class="flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto px-6 py-8 w-full gap-4">
            <p class="text-sm text-center md:text-left">
                &copy; {{ now()->year }} Sahabat Aman &mdash; Layanan Bimbingan &amp; Konseling Sekolah.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-semibold">
                <a class="hover:text-primary transition-colors" href="#quotes">Quotes Hari Ini</a>
                <a class="hover:text-primary transition-colors" href="#kerahasiaan">Etika Kerahasiaan</a>
                @guest
                    <a class="hover:text-primary transition-colors" href="{{ route('login') }}">Masuk</a>
                @else
                    <a class="hover:text-primary transition-colors" href="{{ route('profile') }}">Profil Saya</a>
                @endguest
            </div>
        </div>
    </footer>

</body>

</html>
