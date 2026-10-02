<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Guru BK - Sahabat Aman')</title>
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(112, 121, 115, 0.25);
            border-radius: 9999px;
        }

        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        #adminSidebar {
            transition: translate .3s cubic-bezier(0.16, 1, 0.3, 1), transform .3s cubic-bezier(0.16, 1, 0.3, 1), width .3s cubic-bezier(0.16, 1, 0.3, 1), padding .3s;
        }

        #adminMainCol {
            transition: margin-left .3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (max-width: 1023.98px) {
            body.sidebar-open #adminSidebar {
                translate: 0 0 !important;
                transform: translateX(0) !important;
            }

            body.sidebar-open #sidebarOverlay {
                opacity: 1;
                pointer-events: auto;
            }
        }

        @media (min-width: 1024px) {
            body.sidebar-collapsed #adminSidebar {
                width: 92px !important;
            }

            body.sidebar-collapsed #adminSidebar .sidebar-label {
                display: none !important;
            }

            body.sidebar-collapsed #sidebarHead {
                flex-direction: column;
                align-items: center;
                gap: 12px;
                padding-left: 0;
                padding-right: 0;
            }

            body.sidebar-collapsed #adminSidebar .sidebar-nav-link {
                justify-content: center;
                align-items: center;
                width: 52px;
                height: 52px;
                margin-left: auto;
                margin-right: auto;
                padding: 0;
                border-radius: 16px;
            }

            body.sidebar-collapsed #adminSidebar .sidebar-nav-link .material-symbols-outlined {
                font-size: 24px;
            }

            body.sidebar-collapsed #adminMainCol {
                margin-left: 92px !important;
                padding-left: 0 !important;
            }

            body.sidebar-collapsed #adminProfileCard {
                justify-content: center;
                padding: 8px;
                background: transparent;
                border-color: transparent;
            }

            body.sidebar-collapsed #adminLogoutBtn {
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

        #adminSidebar,
        #adminSidebar nav {
            overscroll-behavior: contain;
        }

        html.drawer-locked,
        html.drawer-locked body {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-background text-on-surface font-sans antialiased min-h-screen flex"
    data-swal-flash-success="{{ session('success') }}" data-swal-flash-info="{{ session('info') }}">

    {{-- Overlay drawer HP --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-40 lg:hidden"></div>

    {{-- Sidebar saja (tanpa topbar, tanpa dropdown): hamburger collapse --}}
    <aside id="adminSidebar"
        class="fixed top-0 left-0 h-dvh w-72 max-w-[85vw] flex flex-col bg-surface-container-low border-r border-outline-variant z-50 -translate-x-full lg:translate-x-0 overflow-hidden">
        <div class="flex flex-col h-full p-4 w-full gap-6 min-h-0 flex-1">
            <div class="flex flex-col gap-6 min-h-0 flex-1">
                {{-- Brand + hamburger --}}
                <div id="sidebarHead" class="flex items-center justify-between gap-2 px-2 pt-2 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-10 h-10 shrink-0 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                            <span class="material-symbols-outlined text-2xl">spa</span>
                        </div>
                        <div class="sidebar-label flex flex-col min-w-0">
                            <span class="text-headline-sm font-bold text-primary leading-tight truncate">Sahabat
                                Aman</span>
                        </div>
                    </div>
                    <button id="btnSidebar" type="button"
                        class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors shrink-0"
                        aria-label="Buka / tutup sidebar" aria-expanded="true" aria-controls="adminSidebar"
                        title="Buka / tutup sidebar">
                        <span id="btnSidebarIcon" class="material-symbols-outlined">menu</span>
                    </button>
                </div>

                <nav aria-label="Menu Utama Konseling"
                    class="flex flex-col gap-1.5 overflow-y-auto custom-scrollbar min-h-0">
                    <a class="sidebar-nav-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.index', 'admin.show') ? 'bg-primary-container text-on-primary-container font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors"
                        href="{{ route('admin.index') }}" title="Dashboard Admin">
                        <span class="material-symbols-outlined shrink-0">dashboard</span>
                        <span class="sidebar-label truncate">Dashboard Admin</span>
                    </a>
                    <a class="sidebar-nav-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.quotes.*') ? 'bg-primary-container text-on-primary-container font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors"
                        href="{{ route('admin.quotes.index') }}" title="Kelola Quotes">
                        <span class="material-symbols-outlined shrink-0">format_quote</span>
                        <span class="sidebar-label truncate">Kelola Quotes</span>
                    </a>
                    <a class="sidebar-nav-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.login-display.*') ? 'bg-primary-container text-on-primary-container font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors"
                        href="{{ route('admin.login-display.index') }}" title="Tampilan Halaman Login">
                        <span class="material-symbols-outlined shrink-0">tune</span>
                        <span class="sidebar-label truncate">Tampilan Halaman Login</span>
                    </a>
                </nav>
            </div>
            {{-- Profile Section --}}
            <div class="flex flex-col gap-3 pt-4 mt-6 border-t border-surface-variant/40 shrink-0">
                <a href="{{ route('admin.account') }}" id="adminProfileCard"
                    class="w-full p-3.5 bg-surface-container-low rounded-2xl flex items-center gap-3 hover:bg-surface-container transition-colors group"
                    title="Profil Saya">
                    <span class="sidebar-label flex-1 min-w-0">
                        <span
                            class="block text-label-lg font-bold truncate text-on-surface group-hover:text-primary transition-colors">{{ Auth::user()->name }}</span>
                        <span class="flex items-center gap-1 text-label-sm text-outline">
                            Guru BK
                        </span>
                    </span>
                    <span
                        class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-lg">chevron_right</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="adminLogoutBtn" type="submit"
                        class="flex items-center gap-3 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-xl px-4 py-2.5 font-normal transition-colors duration-150 w-full"
                        title="Keluar">
                        <span class="material-symbols-outlined text-lg shrink-0">logout</span>
                        <span class="sidebar-label text-label-md">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div id="adminMainCol" class="flex-1 flex flex-col min-w-0 min-h-screen lg:pl-72">
        <main class="sm:p-6 lg:p-8 min-h-screen bg-background mx-auto flex flex-col pb-28 lg:pb-12">
            @yield('content')
            <nav class="lg:hidden fixed bottom-0 left-0 w-full z-30 bg-surface-container-lowest border-t border-surface-variant/40 px-2 pt-1.5 pb-safe shadow-[0_-4px_20px_-4px_rgba(0,0,0,0.08)]"
                aria-label="Navigasi bawah">
                <div class="grid grid-cols-4 gap-0.5 min-[400px]:gap-1 max-w-md mx-auto">
                    <a href="{{ route('admin.index') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('admin.index', 'admin.show') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">dashboard</span>
                        <span
                            class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.login-display.index') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('admin.login-display.*') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">tune</span>
                        <span
                            class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Setting Tampilan</span>
                    </a>
                    <a href="{{ route('admin.quotes.index') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('admin.quotes.*') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">format_quote</span>
                        <span
                            class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Quotes</span>
                    </a>
                    <a href="{{ route('admin.account') }}"
                        class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-15 min-w-0 transition-colors {{ request()->routeIs('admin.account') ? 'bg-primary-container/60 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">person</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Pengaturan
                            Akun</span>
                    </a>
                </div>
            </nav>

        </main>
    </div>

    @yield('scripts')
</body>

</html>
