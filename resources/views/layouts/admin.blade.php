<!DOCTYPE html>
<html lang="ar" dir="rtl" class="bg-gray-50 dark:bg-gray-900" x-data="{ menuOpen:false }" x-cloak>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'لوحة إدارة تطبيق الخوارزمي')</title>

    <!-- CSS & JS -->
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak]{ display:none !important; }
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>

<body class="text-gray-800 dark:text-gray-100">

    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-600 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 text-white shadow sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between py-2.5 px-4 md:px-6">

            <!-- Logo + Title -->
            <div class="flex items-center gap-2 select-none">
                <div class="flex items-center justify-center bg-white/10 rounded-lg overflow-hidden shadow-sm p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="شعار الخوارزمي" class="w-6 h-6 md:w-7 md:h-7 object-contain">
                </div>
                <h1 class="text-lg md:text-xl font-bold flex items-center gap-2 leading-none">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                    لوحة الإدارة
                </h1>
            </div>

            <!-- Mobile menu button -->
            <button @click="menuOpen = !menuOpen"
                    class="md:hidden p-2 rounded-lg bg-white/10 hover:bg-white/20 transition"
                    aria-label="Toggle menu"
                    :aria-expanded="menuOpen.toString()">
                <i data-lucide="menu" x-show="!menuOpen" class="w-6 h-6"></i>
                <i data-lucide="x" x-show="menuOpen" class="w-6 h-6"></i>
            </button>

            @php
                $navBase = 'px-3 py-2 rounded-lg transition text-white/80 hover:text-white hover:bg-indigo-600';
                $navActive = 'bg-indigo-600 text-white font-semibold shadow-inner';
            @endphp

            <!-- Desktop navigation -->
            <nav class="hidden md:flex items-center gap-2 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : '' }}">📊 الرئيسية</a>
                <a href="{{ route('admin.lessons.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.lessons.*') ? $navActive : '' }}">📘 الدروس</a>
                <a href="{{ route('admin.articles.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.articles.*') ? $navActive : '' }}">📰 المقالات</a>
                <a href="{{ route('admin.users.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.users.*') ? $navActive : '' }}">👥 المستخدمون</a>
                <a href="{{ route('admin.statistics') }}" class="{{ $navBase }} {{ request()->routeIs('admin.statistics') ? $navActive : '' }}">📈 الإحصاءات</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3 py-2 rounded-lg transition text-red-200 hover:text-white hover:bg-red-600">
                        🚪 خروج
                    </button>
                </form>
            </nav>
        </div>

        <!-- Mobile menu -->
        <div x-show="menuOpen" x-transition.origin.top
             class="md:hidden border-t border-white/20 bg-indigo-700/95 dark:bg-gray-900/95 backdrop-blur-md">
            <div class="container mx-auto px-4 py-3 flex flex-col gap-2 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : '' }}">📊 الرئيسية</a>
                <a href="{{ route('admin.lessons.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.lessons.*') ? $navActive : '' }}">📘 الدروس</a>
                <a href="{{ route('admin.articles.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.articles.*') ? $navActive : '' }}">📰 المقالات</a>
                <a href="{{ route('admin.users.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.users.*') ? $navActive : '' }}">👥 المستخدمون</a>
                <a href="{{ route('admin.statistics') }}" class="{{ $navBase }} {{ request()->routeIs('admin.statistics') ? $navActive : '' }}">📈 الإحصاءات</a>

                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-white/20">
                    @csrf
                    <button type="submit"
                            class="px-3 py-2 rounded-lg transition text-red-200 hover:text-white hover:bg-red-600">
                        🚪 خروج
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="container mx-auto p-6">
        @yield('content')
    </main>

    <!-- Init Lucide icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
