<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '🎮 اللعبة التعليمية')</title>

    <!-- Arabic font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;700;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (dev only) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind custom config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#3b82f6',
                        success: '#22c55e',
                        warning: '#fbbf24',
                    },
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <style>
        /* Base styles */
        body {
            font-family: "Cairo", sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #dbeafe, #eff6ff, #e0f2fe);
        }

        /* Dark mode background */
        .dark body {
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
        }

        /* SweetAlert Arabic font */
        .swal2-popup {
            font-family: "Cairo", sans-serif !important;
        }

        /* Loading screen animation */
        #loadingGame {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
    </style>
</head>

<body class="text-gray-800 dark:text-gray-100 bg-gradient-to-br from-sky-100 via-white to-sky-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-500">

    <!-- Game content -->
    @yield('content')

    <!-- Loading overlay -->
    <div id="loadingGame" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center text-white text-2xl font-bold z-50">
        ⏳ جاري تحميل اللعبة...
    </div>

    <!-- No JS warning -->
    <noscript>
        <div class="fixed inset-0 flex items-center justify-center bg-black/70 text-white text-2xl font-bold z-50">
            ⚠️ الرجاء تفعيل JavaScript لتشغيل اللعبة.
        </div>
    </noscript>

    <!-- Notification sound -->
    <audio id="notifySound" preload="auto">
        <source src="{{ asset('sounds/notify.mp3') }}" type="audio/mpeg">
    </audio>

    <!-- Initialize Lucide icons -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();
        });
    </script>

</body>
</html>
