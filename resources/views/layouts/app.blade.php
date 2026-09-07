<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sahabat Aman')</title>
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
        .soft-shadow {
            box-shadow: 0 4px 20px -2px rgba(45, 106, 79, 0.05), 0 1px 3px 0 rgba(0, 0, 0, 0.02);
        }
        .hover-lift {
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(45, 106, 79, 0.08), 0 4px 8px -2px rgba(45, 106, 79, 0.03);
        }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #bfc9c1; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #707973; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
    </style>
</head>
<body class="bg-background font-sans text-on-surface min-h-screen antialiased flex selection:bg-primary-fixed selection:text-on-primary-fixed">

    {{-- Desktop Side Navigation (hanya untuk user yang sudah login) --}}
    @auth
    <aside class="fixed top-0 left-0 h-full w-72 bg-surface-container-lowest z-40 hidden lg:flex flex-col justify-between p-6 shadow-md border-r border-surface-variant/40">
        <div class="flex flex-col gap-6">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-2xl bg-primary flex items-center justify-center text-on-primary shadow-sm">
                    <span class="material-symbols-outlined">spa</span>
                </div>
                <div>
                    <h1 class="text-headline-sm font-bold text-primary tracking-tight">Sahabat Aman</h1>
                    <p class="text-label-sm text-outline">Sistem Konseling Terjaga & Aman</p>
                </div>
            </div>

            <div class="p-3.5 bg-surface-container-low rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-base">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-label-lg font-bold truncate text-on-surface">{{ Auth::user()->name }}</p>
                    <span class="inline-flex items-center gap-1 text-label-sm text-outline">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span>
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
            </div>

            <nav class="flex flex-col gap-1.5">
                <a class="flex items-center gap-3 {{ request()->routeIs('curhat.create') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150" href="{{ route('curhat.create') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('curhat.create') ? 'text-primary' : 'text-outline' }}">add_notes</span>
                    <span class="text-label-lg">Buat Curhat Baru</span>
                </a>
                <a class="flex items-center justify-between {{ request()->routeIs('dashboard') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150" href="{{ route('dashboard') }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-outline' }}">chat_bubble</span>
                        <span class="text-label-lg">Kotak Masuk & Dialog</span>
                    </div>
                </a>
                <a class="flex items-center gap-3 {{ request()->routeIs('riwayat*') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150" href="{{ route('riwayat') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('riwayat*') ? 'text-primary' : 'text-outline' }}">history_edu</span>
                    <span class="text-label-lg">Riwayat Curhat</span>
                </a>
                @if(Auth::user()->role === 'guru')
                <a class="flex items-center gap-3 {{ request()->routeIs('admin.*') ? 'bg-surface-container text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }} rounded-xl px-4 py-3 transition-colors duration-150" href="{{ route('admin.index') }}">
                    <span class="material-symbols-outlined {{ request()->routeIs('admin.*') ? 'text-primary' : 'text-outline' }}">dashboard</span>
                    <span class="text-label-lg">Panel Guru BK</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="flex flex-col gap-3 pt-4 border-t border-surface-variant/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-on-surface-variant hover:bg-surface-container hover:text-primary rounded-xl px-4 py-2.5 font-normal transition-colors duration-150 w-full">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    <span class="text-label-md">Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    {{-- Mobile Top Bar (hanya untuk user login) --}}
    @auth
    <header class="lg:hidden fixed top-0 left-0 w-full bg-surface-container-lowest z-30 px-4 py-3 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined text-sm">spa</span>
            </div>
            <span class="text-title-md font-bold text-primary">Sahabat Aman</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </header>
    @endauth

    {{-- Main Content --}}
    <main class="flex-1 min-h-screen px-4 sm:px-8 max-w-7xl mx-auto flex flex-col gap-8 @auth lg:ml-72 @endauth pt-16 lg:pt-8 pb-24 lg:pb-12">
        @if(session('success'))
        <div class="bg-primary-fixed/40 border border-primary/20 rounded-2xl px-5 py-3 text-sm font-semibold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @yield('content')
    </main>

    {{-- Mobile Bottom Navigation --}}
    @auth
    <nav class="lg:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 pb-safe bg-surface-container-lowest shadow-lg border-t border-surface-variant/30">
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('curhat.create') ? 'bg-primary-container text-on-primary-container rounded-xl' : 'text-on-surface-variant' }} px-3 py-1.5 transition-transform duration-150" href="{{ route('curhat.create') }}">
            <span class="material-symbols-outlined text-xl">add_notes</span>
            <span class="text-label-sm mt-0.5">Curhat</span>
        </a>
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-primary-container text-on-primary-container rounded-xl' : 'text-on-surface-variant' }} px-3 py-1.5 transition-transform duration-150 relative" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-xl">chat_bubble</span>
            <span class="text-label-sm mt-0.5">Pesan</span>
        </a>
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('riwayat*') ? 'bg-primary-container text-on-primary-container rounded-xl' : 'text-on-surface-variant' }} px-3 py-1.5 transition-transform duration-150" href="{{ route('riwayat') }}">
            <span class="material-symbols-outlined text-xl">bookmark_heart</span>
            <span class="text-label-sm mt-0.5">Riwayat</span>
        </a>
    </nav>
    @endauth

    @yield('scripts')
</body>
</html>
