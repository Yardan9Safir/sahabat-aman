<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sahabat Aman')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F6F3EC] text-[#1B1B1B] antialiased">
    <div class="min-h-screen">
        <header class="sticky top-0 z-20 border-b border-[#DAD3C8] bg-[#F6F3EC]/90 backdrop-blur-sm">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-[#4F6D62]">Sahabat Aman</a>
                </div>
                <nav class="hidden items-center gap-6 text-sm font-medium text-[#335C50] md:flex">
                    @auth
                        @if(Auth::user()->role === 'siswa')
                            <a href="{{ route('curhat.create') }}" class="hover:text-[#2D4F46]">Curhat</a>
                            <a href="{{ route('riwayat') }}" class="hover:text-[#2D4F46]">Riwayat</a>
                            <a href="{{ route('dashboard') }}" class="hover:text-[#2D4F46]">Inbox</a>
                        @else
                            <a href="{{ route('admin.index') }}" class="hover:text-[#2D4F46]">Panel Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hover:text-[#2D4F46]">Masuk</a>
                        <a href="{{ route('register') }}" class="hover:text-[#2D4F46]">Daftar</a>
                    @endauth
                </nav>
                <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="rounded-lg p-2 text-[#335C50] md:hidden" aria-label="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex items-center gap-3">
                    @auth
                        <span class="hidden rounded-full bg-[#E7F2EC] px-3 py-1 text-xs font-semibold text-[#3C685E] sm:inline-block">{{ ucfirst(Auth::user()->name) }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full bg-[#5B7F71] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#486A5E]">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-[#7C9885] px-4 py-2 text-sm font-semibold text-[#385C4F]">Masuk</a>
                    @endauth
                </div>
            </div>
            <div id="mobileMenu" class="hidden border-t border-[#DAD3C8] bg-[#F6F3EC] px-4 pt-2 pb-3 md:hidden">
                <nav class="flex flex-col gap-3 text-sm font-medium text-[#335C50]">
                    @auth
                        @if(Auth::user()->role === 'siswa')
                            <a href="{{ route('curhat.create') }}" class="rounded-lg px-2 py-2 hover:bg-[#EDE7DB]">Curhat</a>
                            <a href="{{ route('riwayat') }}" class="rounded-lg px-2 py-2 hover:bg-[#EDE7DB]">Riwayat</a>
                            <a href="{{ route('dashboard') }}" class="rounded-lg px-2 py-2 hover:bg-[#EDE7DB]">Inbox</a>
                        @else
                            <a href="{{ route('admin.index') }}" class="rounded-lg px-2 py-2 hover:bg-[#EDE7DB]">Panel Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-2 py-2 hover:bg-[#EDE7DB]">Masuk</a>
                        <a href="{{ route('register') }}" class="rounded-lg px-2 py-2 hover:bg-[#EDE7DB]">Daftar</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
