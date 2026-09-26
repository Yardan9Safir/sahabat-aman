<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sahabat Aman')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
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

        .material-symbols-outlined.hidden {
            display: none !important;
        }

        .soft-shadow {
            box-shadow: 0 4px 20px -2px rgba(8, 145, 178, 0.05), 0 1px 3px 0 rgba(0, 0, 0, 0.02);
        }

        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #c6c7bf;
            border-radius: 9999px;
        }

        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        /* Sidebar: hamburger collapse (desktop) + drawer (HP) */
        #appSidebar {
            transition: translate .3s cubic-bezier(0.16, 1, 0.3, 1), transform .3s cubic-bezier(0.16, 1, 0.3, 1), width .3s cubic-bezier(0.16, 1, 0.3, 1), padding .3s;
        }

        #appMainCol {
            transition: margin-left .3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (max-width: 1023.98px) {
            body.sidebar-open #appSidebar {
                translate: 0 0 !important;
                transform: translateX(0) !important;
            }

            body.sidebar-open #sidebarOverlay {
                opacity: 1;
                pointer-events: auto;
            }
        }

        @media (min-width: 1024px) {
            body.sidebar-collapsed #appSidebar {
                width: 92px !important;
                padding-left: 12px;
                padding-right: 12px;
            }

            body.sidebar-collapsed #appSidebar .sidebar-label {
                display: none !important;
            }

            body.sidebar-collapsed #sidebarHead {
                flex-direction: column;
                align-items: center;
                gap: 12px;
                padding-left: 0;
                padding-right: 0;
            }

            body.sidebar-collapsed #appSidebar .sidebar-nav-link {
                justify-content: center;
                align-items: center;
                width: 52px;
                height: 52px;
                margin-left: auto;
                margin-right: auto;
                padding: 0;
                border-radius: 16px;
            }

            body.sidebar-collapsed #appSidebar .sidebar-nav-link .material-symbols-outlined {
                font-size: 24px;
            }

            body.sidebar-collapsed #appMainCol {
                margin-left: 92px !important;
            }

            body.sidebar-collapsed #profileCard {
                justify-content: center;
                padding: 10px;
                background: transparent;
            }

            body.sidebar-collapsed #logoutBtn {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
        }

        #sidebarOverlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }

        #appSidebar,
        #appSidebar nav {
            overscroll-behavior: contain;
        }

        html.drawer-locked,
        html.drawer-locked body {
            overflow: hidden;
        }
    </style>
</head>

<body
    class="bg-background font-sans text-on-surface min-h-screen antialiased flex selection:bg-primary-fixed selection:text-on-primary-fixed"
    data-swal-flash-success="{{ session('success') }}" data-swal-flash-info="{{ session('info') }}">

    @auth
        {{-- Overlay drawer HP --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-40 lg:hidden"></div>

        {{-- Sidebar saja (tanpa topbar, tanpa dropdown): hamburger untuk collapse --}}
        <aside id="appSidebar"
            class="fixed top-0 left-0 h-dvh w-72 max-w-[85vw] bg-surface-container-lowest z-50 flex flex-col p-6 shadow-md border-r border-surface-variant/40 -translate-x-full lg:translate-x-0 overflow-hidden">
            <div class="flex flex-col gap-6 min-h-0 flex-1">
                {{-- Brand + hamburger --}}
                <div id="sidebarHead" class="flex items-center justify-between gap-2 px-2 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-10 h-10 shrink-0 rounded-2xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                            <span class="material-symbols-outlined">spa</span>
                        </div>
                        <div class="sidebar-label min-w-0">
                            <h1 class="text-headline-sm font-bold text-primary tracking-tight leading-none truncate">Sahabat
                                Aman</h1>
                        </div>
                    </div>
                    <button id="btnSidebar" type="button"
                        class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors shrink-0"
                        aria-label="Buka / tutup sidebar" aria-expanded="true" aria-controls="appSidebar"
                        title="Buka / tutup sidebar">
                        <span id="btnSidebarIcon" class="material-symbols-outlined">menu</span>
                    </button>
                </div>

                <nav class="flex flex-col gap-1.5 overflow-y-auto custom-scroll min-h-0" aria-label="Navigasi utama">
                    @if (Auth::user()->role === 'siswa')
                        <a class="sidebar-nav-link flex items-center gap-3 {{ request()->routeIs('curhat.create') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150"
                            href="{{ route('curhat.create') }}" title="Buat Curhat Baru">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->routeIs('curhat.create') ? 'text-primary' : 'text-outline' }}">add_notes</span>
                            <span class="sidebar-label text-label-lg truncate">Buat Curhat Baru</span>
                        </a>
                    @endif
                    <a class="sidebar-nav-link flex items-center gap-3 {{ request()->routeIs('dashboard', 'dashboard.show', 'dashboard.reply', 'dashboard.handle') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150"
                        href="{{ route('dashboard') }}" title="Kotak Masuk & Dialog">
                        <span
                            class="material-symbols-outlined shrink-0 {{ request()->routeIs('dashboard', 'dashboard.show', 'dashboard.reply', 'dashboard.handle') ? 'text-primary' : 'text-outline' }}">chat_bubble</span>
                        <span class="sidebar-label text-label-lg truncate">Kotak Masuk & Dialog</span>
                    </a>
                    <a class="sidebar-nav-link flex items-center gap-3 {{ request()->routeIs('quotes.*') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150"
                        href="{{ route('quotes.index') }}" title="Quotes & Kata-Kata">
                        <span
                            class="material-symbols-outlined shrink-0 {{ request()->routeIs('quotes.*') ? 'text-primary' : 'text-outline' }}">format_quote</span>
                        <span class="sidebar-label text-label-lg truncate">Quotes & Kata-Kata</span>
                    </a>
                </nav>
            </div>

            {{-- Profil + tombol keluar --}}
            <div class="flex flex-col gap-3 pt-4 mt-6 border-t border-surface-variant/40 shrink-0">
                <a href="{{ route('profile') }}" id="profileCard"
                    class="w-full p-3.5 bg-surface-container-low rounded-2xl flex items-center gap-3 hover:bg-surface-container transition-colors group"
                    title="Profil Saya">
                    <span class="sidebar-label flex-1 min-w-0">
                        <span
                            class="block text-label-lg font-bold truncate text-on-surface group-hover:text-primary transition-colors">{{ Auth::user()->name }}</span>
                        <span class="flex items-center gap-1 text-label-sm text-outline">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span>
                            {{ Auth::user()->roleLabel() }}
                        </span>
                    </span>
                    <span
                        class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-lg">chevron_right</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="logoutBtn" type="submit"
                        class="flex items-center gap-3 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-xl px-4 py-2.5 font-normal transition-colors duration-150 w-full"
                        title="Keluar">
                        <span class="material-symbols-outlined text-lg shrink-0">logout</span>
                        <span class="sidebar-label text-label-md">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
    @endauth

    {{-- Konten --}}
    <div id="appMainCol" class="flex-1 flex flex-col min-w-0 min-h-screen @auth lg:ml-72 @endauth">
        <main
            class="flex-1 w-full px-4 sm:px-8 max-w-7xl mx-auto flex flex-col gap-8 @auth pt-6 lg:pt-8 pb-28 lg:pb-12 @else py-10 @endauth">
            @yield('content')
        </main>

        @auth
            {{-- Bottom nav HP dibenahi + tombol Menu untuk buka sidebar (pengganti topbar) --}}
            <nav class="lg:hidden fixed bottom-0 left-0 w-full z-30 bg-surface-container-lowest border-t border-surface-variant/40 px-2 pt-1.5 pb-safe shadow-[0_-4px_20px_-4px_rgba(0,0,0,0.08)]"
                aria-label="Navigasi bawah">
                @php $isSiswa = Auth::user()->role === 'siswa'; @endphp
                @php $isAdmin = Auth::user()->role === 'guru'; @endphp
                <div class="grid grid-cols-{{ $isSiswa ? '4' : '3' }} gap-0.5 min-[400px]:gap-1 max-w-md mx-auto">
                    @if ($isSiswa)
                        <a href="{{ route('curhat.create') }}"
                            class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('curhat.create') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                            <span class="material-symbols-outlined text-[22px] leading-none">add_notes</span>
                            <span
                                class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Curhat</span>
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('dashboard', 'dashboard.show', 'dashboard.reply', 'dashboard.handle') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">

                        <span class="material-symbols-outlined text-[22px] leading-none">chat_bubble</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Pesan</span>
                    </a>
                    <a href="{{ route('quotes.index') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('quotes.*') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">format_quote</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Quotes</span>
                    </a>
                    <a href="{{ route('profile') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('profile') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">person</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Akun</span>
                    </a>
                </div>
            </nav>
        @endauth
    </div>

    @yield('scripts')
</body>

</html>
