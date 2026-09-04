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
        .custom-shadow-resting {
            box-shadow: 0 4px 16px -2px rgba(45, 106, 79, 0.04), 0 1px 3px 0 rgba(0, 0, 0, 0.02);
        }
        .custom-shadow-interactive:hover {
            box-shadow: 0 12px 28px -4px rgba(45, 106, 79, 0.08), 0 4px 8px -2px rgba(45, 106, 79, 0.03);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(112, 121, 115, 0.25); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(45, 106, 79, 0.45); }
    </style>
</head>
<body class="bg-background text-on-surface font-sans antialiased min-h-screen flex">

    {{-- Sidebar Navigation --}}
    <aside class="fixed top-0 left-0 h-full w-72 flex flex-col justify-between bg-surface-container-low border-r border-outline-variant z-40">
        <div class="flex flex-col h-full p-4 w-72 justify-between">
            <div class="flex flex-col gap-6">
                {{-- Brand Header --}}
                <div class="flex items-center gap-3 px-2 pt-2">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                        <span class="material-symbols-outlined text-2xl">spa</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-headline-sm font-bold text-primary leading-tight">Sahabat Aman</span>
                        <span class="text-label-sm text-on-surface-variant">Portal Guru BK</span>
                    </div>
                </div>

                {{-- Main Nav Links --}}
                <nav aria-label="Menu Utama Konseling" class="flex flex-col gap-1.5">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.index', 'admin.show') ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors" href="{{ route('admin.index') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Ikhtisar Konseling</span>
                    </a>
                    <a class="flex items-center justify-between px-4 py-3 rounded-xl {{ request()->routeIs('admin.siswa*') ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} transition-colors" href="{{ route('admin.siswa') }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">school</span>
                            <span>Data Siswa</span>
                        </div>
                    </a>
                </nav>
            </div>

            {{-- Footer --}}
            <div class="flex flex-col gap-4 border-t border-outline-variant/60 pt-4">
                <div class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.account') ? 'bg-surface-container-high text-on-surface font-bold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} text-label-md transition-colors" href="{{ route('admin.account') }}">
                        <span class="material-symbols-outlined text-xl">settings</span>
                        <span>Pengaturan Akun</span>
                    </a>
                </div>

                <div class="flex items-center gap-3 p-2 rounded-xl bg-surface-container-lowest border border-outline-variant/50">
                    <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-label-md text-on-surface font-bold truncate">{{ Auth::user()->name }}</span>
                        <span class="text-label-sm text-primary font-medium truncate">Guru BK Pengampu</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors" title="Keluar">
                            <span class="material-symbols-outlined text-lg">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-w-0 pl-72">
        {{-- Top Navigation Bar --}}
        <header class="fixed top-0 right-0 left-72 z-30 bg-surface border-b border-outline-variant">
            <div class="flex justify-between items-center w-full px-6 py-3">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-title-md font-bold text-primary">Selamat Datang, {{ Auth::user()->name }}</h1>
                        <p class="text-body-sm text-on-surface-variant">Portal Konseling & Bimbingan BK</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 bg-surface-container px-3.5 py-1.5 rounded-full border border-primary/20">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-label-md text-primary font-semibold">Status Konseling: Siap Merespon</span>
                    </div>
                    {{-- <button class="relative p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors" title="Notifikasi">
                        <span class="material-symbols-outlined">notifications_active</span>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full ring-2 ring-surface"></span>
                    </button>
                    <button class="p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-lg transition-colors" title="Bantuan">
                        <span class="material-symbols-outlined">help_outline</span>
                    </button> --}}
                </div>
            </div>
        </header>

        <main class="mt-[61px] p-6 lg:p-8 min-h-screen bg-background pb-12">
            @if(session('success'))
            <div class="mb-6 bg-primary-fixed/40 border border-primary/20 rounded-2xl px-5 py-3 text-sm font-semibold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                {{ session('success') }}
            </div>
            @endif
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
