<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'تطبيق الخوارزمي') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,600,700&display=swap" rel="stylesheet" />

    <!-- Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: "Cairo", sans-serif;
        }

       
        .gradient-bg {
            background: linear-gradient(135deg, #4338ca, #4f46e5, #6366f1, #3b82f6);
            background-size: 300% 300%;
            animation: gradientMove 12s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

       
        .logo-frame {
            width: 400px;
            height: 368px;
            border-radius: 10px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            margin: 0 auto;
            background: linear-gradient(135deg, #818cf8, #3b82f6, #6366f1);
        }

     
        .logo-inner {
            width: 100%;
            height: 100%;
            border-radius: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .logo-inner img {
            width: 100%;
            height: auto;
            max-height: 340px;
            border-radius: 20px;
            object-fit: contain;
        }

       
        .auth-card {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            padding: 2rem 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .dark .auth-card {
            background: rgba(17, 24, 39, 0.85);
            border-color: rgba(55, 65, 81, 0.7);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 gradient-bg">

        
    
        <div class="auth-card">
            {{ $slot }}
        </div>

    </div>
</body>
</html>
