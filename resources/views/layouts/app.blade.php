<!DOCTYPE html>
<html lang="ar" dir="rtl" x-data="{ menuOpen: false }" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'تطبيق الخوارزمي')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind + Alpine + Lucide -->
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Fonts & Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f9fafb;
            color: #1e293b;
        }

        header {
            background: linear-gradient(90deg, #4338ca, #4f46e5, #3b82f6);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            background: linear-gradient(90deg, #4f46e5, #6366f1, #3b82f6);
            color: #fff;
        }
    </style>
</head>

<body class="min-h-screen text-gray-800">

    <!-- Header bar -->
    <header class="w-full sticky top-0 z-50">
        <div class="container mx-auto px-4 md:px-6 py-3 flex justify-between items-center text-white">

            <!--  Logo -->
            <div class="flex items-center gap-2 select-none">
                <div class="flex items-center justify-center bg-white/10 rounded-lg p-1 shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="شعار الخوارزمي" class="w-6 h-6 md:w-7 md:h-7 object-contain">
                </div>
                <h1 class="text-lg md:text-xl font-bold flex items-center gap-2 leading-none">
                    <i data-lucide="shield" class="w-5 h-5"></i> تطبيق الخوارزمي
                </h1>
            </div>

            <!--  Desktop nav -->
            <nav class="hidden sm:flex items-center gap-3 text-sm md:text-base">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-white/90 hover:bg-indigo-600/50 transition {{ request()->routeIs('home') ? 'bg-indigo-600/70 text-white font-semibold shadow-inner' : '' }}">🏠 الرئيسية</a>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-white/90 hover:bg-indigo-600/50 transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600/70 text-white font-semibold shadow-inner' : '' }}">📊 لوحة التحكم</a>

                <a href="{{ route('lessons.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-white/90 hover:bg-indigo-600/50 transition {{ request()->routeIs('lessons.*') ? 'bg-indigo-600/70 text-white font-semibold shadow-inner' : '' }}">📚 الدروس</a>

                <a href="{{ route('typing.show', 1) }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-white/90 hover:bg-indigo-600/50 transition {{ request()->routeIs('typing.*') ? 'bg-indigo-600/70 text-white font-semibold shadow-inner' : '' }}">⌨️ التدريب</a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-medium text-white/90 hover:bg-indigo-600/50 transition {{ request()->routeIs('profile.*') ? 'bg-indigo-600/70 text-white font-semibold shadow-inner' : '' }}">👤 الملف الشخصي</a>

                <!--  Logout button -->
                @auth
                <form action="{{ route('logout') }}" method="POST" class="ml-3">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-red-500/80 hover:bg-red-600 text-white font-semibold transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> تسجيل الخروج
                    </button>
                </form>
                @endauth
            </nav>

            <!--  Mobile menu toggle -->
            <button @click="menuOpen = !menuOpen"
                    class="sm:hidden p-2 rounded-lg bg-white/10 hover:bg-white/20 transition">
                <i data-lucide="menu" x-show="!menuOpen" class="w-6 h-6"></i>
                <i data-lucide="x" x-show="menuOpen" class="w-6 h-6"></i>
            </button>
        </div>

        <!--  Mobile dropdown -->
        <div x-show="menuOpen" x-transition.scale.origin.top
             class="sm:hidden bg-indigo-700/95 text-white backdrop-blur-md border-t border-white/20 shadow-lg">
            <div class="flex flex-col p-4 space-y-2 text-base">
                <a href="{{ route('home') }}" @click="menuOpen=false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-600/50 transition {{ request()->routeIs('home') ? 'bg-indigo-600/70 font-semibold shadow-inner' : '' }}">🏠 الرئيسية</a>

                <a href="{{ route('dashboard') }}" @click="menuOpen=false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-600/50 transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600/70 font-semibold shadow-inner' : '' }}">📊 لوحة التحكم</a>

                <a href="{{ route('lessons.index') }}" @click="menuOpen=false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-600/50 transition {{ request()->routeIs('lessons.*') ? 'bg-indigo-600/70 font-semibold shadow-inner' : '' }}">📚 الدروس</a>

                <a href="{{ route('typing.show', 1) }}" @click="menuOpen=false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-600/50 transition {{ request()->routeIs('typing.*') ? 'bg-indigo-600/70 font-semibold shadow-inner' : '' }}">⌨️ التدريب</a>

                <a href="{{ route('profile.edit') }}" @click="menuOpen=false"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-600/50 transition {{ request()->routeIs('profile.*') ? 'bg-indigo-600/70 font-semibold shadow-inner' : '' }}">👤 الملف الشخصي</a>

                @auth
                <form method="POST" action="{{ route('logout') }}" class="pt-3 border-t border-white/20">
                    @csrf
                    <button type="submit"
                            class="flex justify-center items-center gap-2 px-3 py-2 rounded-lg text-red-200 hover:text-red-400">
                        <i data-lucide="log-out" class="w-5 h-5"></i> تسجيل الخروج
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="container mx-auto px-4 md:px-8 py-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-8 py-6 text-center text-sm">
  <x-secure-footer />
    </footer>

    <!-- Init icons -->
    <script> lucide.createIcons(); </script>
</body>
</html>
