<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — الصفحة غير موجودة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-600 to-blue-500 text-white font-sans">

    <div class="text-center space-y-6">
        <h1 class="text-9xl font-extrabold drop-shadow-lg">404</h1>
        <h2 class="text-2xl md:text-3xl font-bold">الصفحة غير موجودة 🚧</h2>
        <p class="text-lg text-indigo-100">
            يبدو أنك حاولت الوصول إلى رابط غير صحيح أو غير متاح حالياً.
        </p>

        <a href="{{ url('/') }}" 
           class="mt-6 inline-block bg-yellow-400 text-indigo-900 font-bold px-6 py-3 rounded-xl hover:bg-yellow-300 transition shadow-lg">
            ⬅️ العودة إلى الصفحة الرئيسية
        </a>
    </div>

    <footer class="mt-10 text-indigo-200 text-sm">
        &copy; {{ date('Y') }} تطبيق الخوارزمي لتعلم الطباعة — جميع الحقوق محفوظة
    </footer>

</body>
</html>
