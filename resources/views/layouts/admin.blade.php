<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Sahabat Aman')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F3F0E9] text-[#1B1B1B] antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 flex-col border-r border-[#DAD3C8] bg-[#2F4B42] lg:flex">
            <div class="px-6 py-6">
                <a href="{{ route('admin.index') }}" class="text-xl font-bold tracking-tight text-white">Sahabat Aman</a>
                <p class="mt-1 text-xs font-medium text-[#A9BFB4]">Panel Admin</p>
            </div>

            <nav class="mt-2 flex-1 space-y-1 px-3">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('admin.index', 'admin.show') ? 'bg-[#3D5F54] text-white' : 'text-[#C6D6CE] hover:bg-[#3D5F54] hover:text-white' }}">
                    <span class="text-lg">💬</span> Data Curhat
                </a>
                <a href="{{ route('admin.siswa') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('admin.siswa') ? 'bg-[#3D5F54] text-white' : 'text-[#C6D6CE] hover:bg-[#3D5F54] hover:text-white' }}">
                    <span class="text-lg">🎓</span> Data Siswa
                </a>
            </nav>

            <div class="border-t border-[#3D5F54] p-4">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-[#A9BFB4]">Guru</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-[#3D5F54] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#486A5E]">Keluar</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1">
            <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 rounded-xl bg-[#DFF5E7] px-4 py-3 text-sm font-semibold text-[#2E6B48]">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>