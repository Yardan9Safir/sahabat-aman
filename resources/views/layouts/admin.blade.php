<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Guru BK - Sahabat Aman')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .material-symbols-outlined.hidden { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(112, 121, 115, 0.25); border-radius: 9999px; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }

        #adminSidebar { transition: translate .3s cubic-bezier(0.16,1,0.3,1), transform .3s cubic-bezier(0.16,1,0.3,1), width .3s cubic-bezier(0.16,1,0.3,1), padding .3s; }
        #adminMainCol { transition: margin-left .3s cubic-bezier(0.16,1,0.3,1); }
        @media (max-width: 1023.98px) {
            body.sidebar-open #adminSidebar { translate: 0 0 !important; transform: translateX(0) !important; }
            body.sidebar-open #sidebarOverlay { opacity: 1; pointer-events: auto; }
        }
        @media (min-width: 1024px) {
            body.sidebar-collapsed #adminSidebar { width: 92px !important; }
            body.sidebar-collapsed #adminSidebar .sidebar-label { display: none !important; }
            body.sidebar-collapsed #sidebarHead { flex-direction: column; align-items: center; gap: 12px; padding-left: 0; padding-right: 0; }
            body.sidebar-collapsed #adminSidebar .sidebar-nav-link { justify-content: center; align-items: center; width: 52px; height: 52px; margin-left: auto; margin-right: auto; padding: 0; border-radius: 16px; }
            body.sidebar-collapsed #adminSidebar .sidebar-nav-link .material-symbols-outlined { font-size: 24px; }
            body.sidebar-collapsed #adminMainCol { margin-left: 92px !important; padding-left: 0 !important; }
            body.sidebar-collapsed #adminProfileCard { justify-content: center; padding: 8px; background: transparent; border-color: transparent; }
            body.sidebar-collapsed #adminLogoutBtn { justify-content: center; padding-left: 0; padding-right: 0; }
        }
        #sidebarOverlay { opacity: 0; pointer-events: none; transition: opacity .25s ease; }
        #adminSidebar, #adminSidebar nav { overscroll-behavior: contain; }
        html.drawer-locked, html.drawer-locked body { overflow: hidden; }
    </style>
</head>
<body class="bg-background text-on-surface font-sans antialiased min-h-screen flex">

    {{-- Overlay drawer HP --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-40 lg:hidden"></div>

    {{-- Sidebar saja (tanpa topbar, tanpa dropdown): hamburger collapse --}}
    <aside id="adminSidebar" class="fixed top-0 left-0 h-dvh w-72 max-w-[85vw] flex flex-col bg-surface-container-low border-r border-outline-variant z-50 -translate-x-full lg:translate-x-0 overflow-hidden">
        <div class="flex flex-col h-full p-4 w-full gap-6 min-h-0 flex-1">
            <div class="flex flex-col gap-6 min-h-0 flex-1">
                {{-- Brand + hamburger --}}
                <div id="sidebarHead" class="flex items-center justify-between gap-2 px-2 pt-2 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 shrink-0 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                            <span class="material-symbols-outlined text-2xl">spa</span>
                        </div>
                        <div class="sidebar-label flex flex-col min-w-0">
                            <span class="text-headline-sm font-bold text-primary leading-tight truncate">Sahabat Aman</span>
                            <span class="text-label-sm text-on-surface-variant">Portal Guru BK</span>
                        </div>
                    </div>
                    <button id="btnSidebar" type="button" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors shrink-0" aria-label="Buka / tutup sidebar" aria-expanded="true" aria-controls="adminSidebar" title="Buka / tutup sidebar">
                        <span id="btnSidebarIcon" class="material-symbols-outlined">menu</span>
                    </button>
                </div>

                <nav aria-label="Menu Utama Konseling" class="flex flex-col gap-1.5 overflow-y-auto custom-scrollbar min-h-0">
                    <a class="sidebar-nav-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.index', 'admin.show') ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors" href="{{ route('admin.index') }}" title="Ikhtisar Konseling">
                        <span class="material-symbols-outlined shrink-0">dashboard</span>
                        <span class="sidebar-label truncate">Ikhtisar Konseling</span>
                    </a>
                    <a class="sidebar-nav-link flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.siswa*') ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors" href="{{ route('admin.siswa') }}" title="Data Siswa">
                        <span class="material-symbols-outlined shrink-0">school</span>
                        <span class="sidebar-label truncate">Data Siswa</span>
                    </a>
                    <a class="sidebar-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" href="{{ route('dashboard') }}" title="Ruang Dialog">
                        <span class="material-symbols-outlined shrink-0">chat_bubble</span>
                        <span class="sidebar-label truncate">Ruang Dialog</span>
                    </a>
                </nav>
            </div>

            {{-- Footer statis (dropdown dihapus) --}}
            <div class="flex flex-col gap-3 border-t border-outline-variant/60 pt-4 shrink-0">
                <a href="{{ route('admin.account') }}" class="sidebar-nav-link hidden lg:flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.account') ? 'bg-surface-container-high text-on-surface font-bold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} text-label-md transition-colors" title="Pengaturan Akun">
                    <span class="material-symbols-outlined text-xl shrink-0">settings</span>
                    <span class="sidebar-label">Pengaturan Akun</span>
                </a>

                <div id="adminProfileCard" class="w-full flex items-center gap-3 p-2 rounded-xl bg-surface-container-lowest border border-outline-variant/50">
                    <span class="w-10 h-10 shrink-0 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </span>
                    <span class="sidebar-label flex flex-col min-w-0 flex-1">
                        <span class="text-label-md text-on-surface font-bold truncate">{{ Auth::user()->name }}</span>
                        <span class="text-label-sm text-primary font-medium truncate">Guru BK Pengampu</span>
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="adminLogoutBtn" type="submit" class="flex items-center gap-3 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface text-label-md transition-colors w-full" title="Keluar">
                        <span class="material-symbols-outlined text-xl shrink-0">logout</span>
                        <span class="sidebar-label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div id="adminMainCol" class="flex-1 flex flex-col min-w-0 min-h-screen lg:pl-72">
        <main class="p-4 sm:p-6 lg:p-8 min-h-screen bg-background pb-28 lg:pb-12">
            @if(session('success'))
            <div class="mb-6 bg-primary-fixed/40 border border-primary/20 rounded-2xl px-5 py-3 text-sm font-semibold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                {{ session('success') }}
            </div>
            @endif
            @yield('content')

            {{-- Bottom nav HP dibenahi + tombol Menu (pengganti topbar) --}}
            <nav class="lg:hidden fixed bottom-0 left-0 w-full z-30 bg-surface-container-lowest border-t border-outline-variant/40 px-2 pt-1.5 pb-safe shadow-[0_-4px_20px_-4px_rgba(0,0,0,0.08)]" aria-label="Navigasi bawah admin">
                <div class="grid grid-cols-5 gap-0.5 min-[400px]:gap-1 max-w-md mx-auto">
                    <a href="{{ route('admin.index') }}" class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-[60px] min-w-0 transition-colors {{ request()->routeIs('admin.index','admin.show') ? 'bg-primary-container/70 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">dashboard</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Ikhtisar</span>
                    </a>
                    <a href="{{ route('admin.siswa') }}" class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-[60px] min-w-0 transition-colors {{ request()->routeIs('admin.siswa*') ? 'bg-primary-container/70 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">school</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Siswa</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-[60px] min-w-0 transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-container/70 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">chat_bubble</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Dialog</span>
                    </a>
                    <a href="{{ route('admin.account') }}" class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-[60px] min-w-0 transition-colors {{ request()->routeIs('admin.account') ? 'bg-primary-container/70 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-[22px] leading-none">settings</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Akun</span>
                    </a>
                    <button id="btnMenuMobile" type="button" class="flex flex-col items-center justify-center gap-0.5 rounded-2xl px-0.5 min-[400px]:px-1 py-2 min-h-[60px] min-w-0 transition-colors text-on-surface-variant hover:bg-surface-container-high" aria-label="Buka menu sidebar">
                        <span class="material-symbols-outlined text-[22px] leading-none">menu</span>
                        <span class="text-[10px] min-[400px]:text-[11px] leading-tight truncate max-w-full">Menu</span>
                    </button>
                </div>
            </nav>
        </main>
    </div>

    <script>
        (function () {
            var body = document.body;
            var btnSidebar = document.getElementById('btnSidebar');
            var btnMenuMobile = document.getElementById('btnMenuMobile');
            var overlay = document.getElementById('sidebarOverlay');
            var icon = document.getElementById('btnSidebarIcon');
            var isDesktop = function () { return window.matchMedia('(min-width: 1024px)').matches; };

            function syncIcon() {
                var isOpen = isDesktop() ? !body.classList.contains('sidebar-collapsed') : body.classList.contains('sidebar-open');
                if (icon) icon.textContent = isOpen ? 'close' : 'menu';
                if (btnSidebar) btnSidebar.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
            function toggleSidebar() {
                if (isDesktop()) {
                    body.classList.toggle('sidebar-collapsed');
                    try { localStorage.setItem('sa-admin-collapsed', body.classList.contains('sidebar-collapsed') ? '1' : '0'); } catch (e) {}
                } else {
                    body.classList.toggle('sidebar-open');
                }
                syncIcon();
            }
            function closeMobile() { body.classList.remove('sidebar-open'); syncIcon(); syncLock(); }
            function syncLock() {
                var locked = !isDesktop() && body.classList.contains('sidebar-open');
                document.documentElement.classList.toggle('drawer-locked', locked);
            }

            if (btnSidebar) btnSidebar.addEventListener('click', function () { toggleSidebar(); syncLock(); });
            if (btnMenuMobile) btnMenuMobile.addEventListener('click', function () { body.classList.add('sidebar-open'); syncIcon(); syncLock(); });
            if (overlay) overlay.addEventListener('click', closeMobile);
            Array.prototype.forEach.call(document.querySelectorAll('#adminSidebar nav a'), function (el) {
                el.addEventListener('click', closeMobile);
            });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeMobile(); } });
            window.addEventListener('resize', function () {
                if (isDesktop()) body.classList.remove('sidebar-open');
                syncIcon(); syncLock();
            });
            try { if (isDesktop() && localStorage.getItem('sa-admin-collapsed') === '1') body.classList.add('sidebar-collapsed'); } catch (e) {}
            syncIcon(); syncLock();
        })();
    </script>

    @yield('scripts')
</body>
</html>
