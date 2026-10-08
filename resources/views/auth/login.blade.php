<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sahabat Aman</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
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

        .soft-shadow-ambient {
            box-shadow: 0 10px 30px -4px rgba(12, 74, 110, .08), 0 4px 12px -2px rgba(251, 146, 60, .05);
        }

        .btn-sky-shadow {
            box-shadow: 0 4px 16px rgba(8, 145, 178, .3);
        }

        .btn-sky-shadow:hover {
            box-shadow: 0 6px 20px rgba(8, 145, 178, .4);
        }
    </style>
</head>

<body
    class="bg-background font-sans text-on-surface antialiased min-h-screen flex flex-col justify-between selection:bg-primary-fixed selection:text-on-primary-fixed"
    data-swal-flash-success="{{ session('success') }}" data-swal-flash-info="{{ session('info') }}">

    {{-- Top app bar (mobile & tablet) --}}
    <header
        class="lg:hidden bg-background/90 backdrop-blur-md border-b border-outline-variant/40 sticky top-0 z-20">
        <div class="flex justify-between items-center w-full px-4 sm:px-6 lg:px-10 xl:px-16 h-16">
            <div class="text-lg sm:text-xl font-bold text-primary flex items-center gap-2 min-w-0">
                <span class="material-symbols-outlined text-primary text-2xl shrink-0">spa</span>
                <span class="tracking-tight text-on-surface truncate">Sahabat Aman</span>
            </div>
            <a aria-label="Kembali ke beranda"
                class="p-2 -mr-2 rounded-xl text-primary hover:bg-primary-fixed/50 transition-colors active:scale-95 duration-150 shrink-0"
                href="{{ route('welcome') }}">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
        </div>
    </header>

    <main class="flex-grow flex w-full min-h-[calc(100vh-4rem)] lg:min-h-screen">
        <div class="flex flex-col lg:flex-row w-full">

            {{-- KIRI: hero, branding, quotes --}}
            <section
                class="w-full lg:w-[54%] xl:w-[52%] bg-gradient-to-br from-background via-primary-fixed/40 to-tertiary-fixed/50 p-6 sm:p-8 md:p-10 xl:p-16 flex flex-col justify-between gap-8 md:gap-10 relative overflow-hidden border-b lg:border-b-0 lg:border-r border-outline-variant/40">
                <div
                    class="absolute -top-24 -left-20 w-96 h-96 rounded-full bg-primary-fixed/50 blur-3xl pointer-events-none"></div>
                <div
                    class="absolute top-1/3 -right-20 w-80 h-80 rounded-full bg-tertiary-fixed/60 blur-3xl pointer-events-none"></div>
                <div
                    class="absolute -bottom-20 left-10 w-96 h-96 rounded-full bg-primary-fixed/40 blur-3xl pointer-events-none"></div>

                {{-- Branding desktop --}}
                <div class="relative z-10 hidden lg:block">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-tr from-primary to-primary-container flex items-center justify-center text-on-primary shadow-md shadow-primary/20 ring-4 ring-primary-fixed/60 shrink-0">
                            <span class="material-symbols-outlined text-2xl"
                                style="font-variation-settings: 'FILL' 1;">spa</span>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-xl font-bold tracking-tight text-on-surface">Sahabat Aman</span>
                            <span class="block text-xs font-medium text-on-surface-variant">
                                Layanan Bimbingan &amp; Konseling Sekolah
                            </span>
                        </div>
                    </div>
                </div>

                @if (count($loginQuotes))
                    {{-- Kartu quotes --}}
                    <div class="relative z-10 w-full max-w-[480px] mx-auto lg:mx-0" id="quoteCarousel">
                        <div
                            class="bg-surface-container-lowest/95 rounded-2xl p-6 md:p-8 soft-shadow-ambient border border-outline-variant/60 relative backdrop-blur-md transition-all duration-300 min-h-[240px] lg:min-h-[300px] flex flex-col justify-between">
                            <div
                                class="absolute -top-5 left-6 w-11 h-11 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-300 flex items-center justify-center text-amber-950 shadow-md shadow-amber-400/25 ring-4 ring-surface-container-lowest">
                                <span class="material-symbols-outlined text-2xl"
                                    style="font-variation-settings: 'FILL' 1;">format_quote</span>
                            </div>

                            {{-- Semua slide ditumpuk di grid yang sama agar tinggi kartu
                                 mengikuti slide terpanjang (tidak melompat saat rotasi). --}}
                            <div class="grid" data-quote-slides>
                                @foreach ($loginQuotes as $index => $quote)
                                    <div data-quote-slide="{{ $index }}"
                                        class="col-start-1 row-start-1 pt-2 {{ $index === 0 ? '' : 'invisible' }}">
                                        <div
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100/70 border border-amber-200/60 text-amber-900 text-xs font-medium mb-4 max-w-full">
                                            <span class="material-symbols-outlined text-base text-amber-700 shrink-0">{{ $quote->category_icon }}</span>
                                            <span class="truncate">{{ $quote->category_label }}</span>
                                        </div>
                                        <blockquote
                                            class="text-lg md:text-xl font-semibold text-on-surface leading-relaxed mb-6 break-words line-clamp-6 lg:line-clamp-5">
                                            &ldquo;{{ $quote->isi_quotes }}&rdquo;
                                        </blockquote>
                                        <div
                                            class="flex items-center gap-3 border-t border-surface-container-low pt-4">
                                            <div
                                                class="w-9 h-9 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-lg">{{ $quote->displayAuthorIcon() }}</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold leading-none text-on-surface truncate">
                                                    {{ $quote->displayAuthor() }}
                                                </p>
                                                <p class="text-xs mt-0.5 text-on-surface-variant truncate">
                                                    {{ $quote->displayAuthorContext() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (count($loginQuotes) > 1)
                                <div class="flex items-center justify-between mt-4 px-2 select-none gap-3">
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button type="button" id="quotePrev" aria-label="Kutipan sebelumnya"
                                            class="w-8 h-8 rounded-full border border-outline-variant bg-surface-container-lowest hover:bg-surface-container text-on-surface flex items-center justify-center transition active:scale-95 cursor-pointer shadow-sm">
                                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                                        </button>
                                        <button type="button" id="quoteNext" aria-label="Kutipan selanjutnya"
                                            class="w-8 h-8 rounded-full border border-outline-variant bg-surface-container-lowest hover:bg-surface-container text-on-surface flex items-center justify-center transition active:scale-95 cursor-pointer shadow-sm">
                                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                                        </button>
                                        <span class="text-xs ml-1 font-medium text-on-surface-variant tabular-nums"><span
                                                id="quoteIndex" class="font-semibold text-on-surface">1</span> / {{ count($loginQuotes) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0" id="quoteDots">
                                        @foreach ($loginQuotes as $index => $quote)
                                            <button type="button" data-index="{{ $index }}"
                                                aria-label="Lihat kutipan {{ $index + 1 }}"
                                                class="quote-dot h-2 rounded-full transition-all duration-200 cursor-pointer {{ $index === 0 ? 'w-6 bg-amber-400 shadow-sm' : 'w-2 bg-primary-fixed hover:bg-primary-fixed-dim' }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="relative z-10 w-full max-w-[480px] mx-auto lg:mx-0">
                        <div
                            class="bg-surface-container-lowest/95 rounded-2xl p-6 md:p-8 soft-shadow-ambient border border-outline-variant/60 backdrop-blur-md">
                            <h2 class="text-xl md:text-2xl font-bold text-on-surface leading-relaxed">
                                Setiap Cerita Berhak Didengar oleh Sahabat Aman
                            </h2>
                            <p class="text-sm text-on-surface-variant mt-3">
                                Bimbingan konseling sekolah yang hangat, terbuka, dan menjaga privasi kamu.
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Badge etika & keamanan --}}
                <div class="relative z-10 w-full max-w-[480px] mx-auto lg:mx-0">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-surface-container-lowest/70 border border-outline-variant/50 backdrop-blur-sm max-w-full">
                        <span class="material-symbols-outlined text-primary text-base shrink-0"
                            style="font-variation-settings: 'FILL' 1;">lock</span>
                        <span class="text-xs text-on-surface-variant">100% Bebas Stigma &bull; Etika Kerahasiaan
                            Terjamin</span>
                    </div>
                </div>
            </section>

            {{-- KANAN: form login --}}
            <section
                class="w-full lg:w-[46%] xl:w-[48%] bg-background flex items-center justify-center p-6 sm:p-8 md:p-10 xl:p-16">
                <div class="w-full max-w-[420px] flex flex-col">
                    <div class="mb-8">
                        <h1 class="text-[26px] md:text-[32px] leading-[34px] md:leading-[40px] font-bold tracking-tight text-on-surface">
                            Selamat Datang !!!
                        </h1>
                        <p class="text-sm text-on-surface-variant mt-2">
                            Tolong masukkan akun Sahabat Aman Anda untuk melanjutkan konseling.
                        </p>
                    </div>

                    @if (session('warning'))
                        <div class="mb-5">
                            @include('component.alert.failed', ['message' => session('warning')])
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-semibold text-on-surface mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary/70">
                                    <span class="material-symbols-outlined text-xl">mail</span>
                                </div>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    autocomplete="username" placeholder="contoh@email.com"
                                    class="w-full h-12 pl-11 pr-4 bg-surface-container-lowest text-on-surface text-sm rounded-xl border border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/15 outline-none transition duration-150 placeholder:text-outline/70 shadow-sm">
                                @error('email')
                                    <p class="mt-2 text-xs text-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-on-surface mb-2">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-primary/70">
                                    <span class="material-symbols-outlined text-xl">lock</span>
                                </div>
                                <input id="password" name="password" type="password" required
                                    autocomplete="current-password" placeholder="Masukkan kata sandi akun"
                                    class="w-full h-12 pl-11 pr-11 bg-surface-container-lowest text-on-surface text-sm rounded-xl border border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/15 outline-none transition duration-150 placeholder:text-outline/70 shadow-sm">
                                <button type="button" id="togglePassword" aria-label="Lihat atau sembunyikan kata sandi"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors cursor-pointer">
                                    <span class="material-symbols-outlined text-xl" id="eyeIcon">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center pt-1">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="remember" value="1" id="remember"
                                    class="w-4 h-4 mt-0.5 shrink-0 rounded border-outline-variant text-primary focus:ring-primary/30 cursor-pointer transition-colors"
                                    @checked(old('remember'))>
                                <span class="text-sm text-on-surface-variant leading-snug">Ingat akun saya di perangkat
                                    ini</span>
                            </label>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full h-12 bg-gradient-to-r from-primary to-primary-container hover:from-primary/90 hover:to-primary text-on-primary text-sm font-semibold rounded-xl btn-sky-shadow flex items-center justify-center gap-2 active:scale-[.99] transition duration-150 cursor-pointer">
                                <span>Masuk ke Akun</span>
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </main>

    @if (count($loginQuotes) > 1)
        <script>
            (() => {
                const root = document.getElementById('quoteCarousel');
                const slides = Array.from(document.querySelectorAll('[data-quote-slide]'));
                const dots = Array.from(document.querySelectorAll('.quote-dot'));
                const counter = document.getElementById('quoteIndex');
                const total = slides.length;

                if (!root || !total) {
                    return;
                }

                let current = 0;
                let timer = null;

                const showSlide = (index) => {
                    current = (index + total) % total;

                    slides.forEach((slide, i) => slide.classList.toggle('invisible', i !== current));

                    dots.forEach((dot, i) => {
                        dot.className = 'quote-dot h-2 rounded-full transition-all duration-200 cursor-pointer ' + (i ===
                            current
                            ? 'w-6 bg-amber-400 shadow-sm'
                            : 'w-2 bg-primary-fixed hover:bg-primary-fixed-dim');
                    });

                    if (counter) {
                        counter.textContent = String(current + 1);
                    }
                };

                const stop = () => {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                };

                const play = () => {
                    stop();
                    timer = setInterval(() => showSlide(current + 1), 7000);
                };

                const go = (index) => {
                    showSlide(index);
                    play();
                };

                document.getElementById('quotePrev')?.addEventListener('click', () => go(current - 1));
                document.getElementById('quoteNext')?.addEventListener('click', () => go(current + 1));
                dots.forEach((dot) => dot.addEventListener('click', () => go(Number(dot.dataset.index))));

                root.addEventListener('mouseenter', stop);
                root.addEventListener('mouseleave', play);
                root.addEventListener('focusin', stop);
                root.addEventListener('focusout', play);

                showSlide(0);
                play();
            })();
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (!toggleBtn || !passwordInput || !eyeIcon) {
                return;
            }

            toggleBtn.addEventListener('click', () => {
                const hidden = passwordInput.getAttribute('type') === 'password';

                passwordInput.setAttribute('type', hidden ? 'text' : 'password');
                eyeIcon.textContent = hidden ? 'visibility_off' : 'visibility';
            });
        });
    </script>

</body>

</html>
