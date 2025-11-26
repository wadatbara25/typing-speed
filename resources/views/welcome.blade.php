<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق الخوارزمي لتعلم الطباعة</title>

    <!-- Icons and font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Cairo", sans-serif;
            background-color: #f9fafb;
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
        .fade-in { opacity: 0; transform: translateY(20px); animation: fadeIn 0.9s forwards; }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
        .progress-bar { height: 10px; border-radius: 9999px; background: linear-gradient(to right, #fde047, #facc15, #eab308); transition: width 0.5s ease; }

        
        .beta-badge {
            position: fixed;
            top: 12px;
            left: 12px;
            background: linear-gradient(90deg, #f59e0b, #facc15);
            color: #1e293b;
            padding: 6px 14px;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 9999px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 6px;
            opacity: 0.95;
        }

        /* ⭐ تقييم النجوم */
        .rating-container {
            margin-top: 2rem;
            text-align: center;
        }
        .stars {
            display: flex;
            justify-content: center;
            gap: 8px;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .stars i:hover {
            transform: scale(1.2);
        }
        .stars i.active {
            color: #facc15;
        }
    </style>
</head>

<body class="text-gray-800 bg-gray-50">

    <div class="beta-badge">
        <i class="fa-solid fa-flask"></i>
        نسخة تجريبية
    </div>

    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-6 py-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-12 w-12 rounded-lg shadow-md">
                <div>
                    <h1 class="text-2xl font-bold">تطبيق الخوارزمي</h1>
                    <p class="text-sm opacity-90">لتعلم الطباعة باللمس</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold">
                <a href="#features" class="hover:text-yellow-300 transition">المميزات</a>
                <a href="#train" class="hover:text-yellow-300 transition">ابدأ التدريب</a>
                <a href="#articles" class="hover:text-yellow-300 transition">المقالات</a>
                <a href="#top" class="hover:text-yellow-300 transition">الأبطال</a>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-yellow-300">لوحة الإدارة</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="hover:text-yellow-300">لوحة التحكم</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow transition">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> تسجيل الخروج
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-yellow-300">تسجيل الدخول</a>
                    <a href="{{ route('register') }}"
                       class="bg-yellow-400 text-indigo-900 px-4 py-2 rounded-lg hover:bg-yellow-300 transition">
                       مستخدم جديد
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative h-[80vh] flex flex-col justify-center items-center text-center text-white gradient-bg overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://maher-typing.com/images/bg-5.png')] bg-cover bg-center animate-pulse"></div>
        <h2 class="text-5xl md:text-6xl font-extrabold mb-4 drop-shadow-lg fade-in">ابدأ رحلتك مع الطباعة السريعة</h2>
        <p class="text-lg md:text-xl text-indigo-100 max-w-2xl mb-6 fade-in">
            تعلم الطباعة باللمس بسهولة، وطور مهاراتك في الكتابة بدقة واحترافية.
        </p>

        @auth
            <div class="w-64 bg-indigo-200 rounded-full overflow-hidden mb-6">
                <div class="progress-bar" style="width: {{ min(100, auth()->user()->progress ?? 60) }}%;"></div>
            </div>
            <p class="text-sm text-indigo-100 mb-4">
                مستوى التقدم: <span class="font-semibold text-yellow-300">{{ auth()->user()->level ?? 'مبتدئ' }}</span>
            </p>
            <a href="{{ route('lessons.index') }}"
               class="bg-yellow-400 hover:bg-yellow-300 text-indigo-900 font-bold px-6 py-3 rounded-xl text-lg transition fade-in">
               واصل التدريب
            </a>
        @else
            <a href="{{ route('login') }}"
               class="bg-yellow-400 hover:bg-yellow-300 text-indigo-900 font-bold px-6 py-3 rounded-xl text-lg transition fade-in">
               سجّل الدخول للبدء
            </a>
        @endauth
    </section>

    <!-- Features -->
    <section id="features" class="py-16 bg-white fade-in">
        <div class="container mx-auto text-center">
            <h3 class="text-3xl font-extrabold text-indigo-700 mb-8">مميزات التطبيق</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-indigo-50 rounded-2xl p-6 shadow hover:shadow-lg hover:scale-105 transition transform">
                    <i class="fa-solid fa-keyboard text-4xl text-indigo-600 mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">تدريب عملي</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">تدريبات تفاعلية لتحسين سرعة الطباعة.</p>
                </div>
                <div class="bg-indigo-50 rounded-2xl p-6 shadow hover:shadow-lg hover:scale-105 transition transform">
                    <i class="fa-solid fa-chart-line text-4xl text-indigo-600 mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">إحصائيات دقيقة</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">تابع سرعتك ودقتك وتقدمك في كل تمرين بسهولة.</p>
                </div>
                <div class="bg-indigo-50 rounded-2xl p-6 shadow hover:shadow-lg hover:scale-105 transition transform">
                    <i class="fa-solid fa-gamepad text-4xl text-indigo-600 mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">ألعاب تعليمية</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">تعلم من خلال ألعاب ممتعة تحفزك على الإتقان.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Top users -->
    <section id="top" class="py-16 bg-white fade-in">
        <div class="container mx-auto text-center">
            <h3 class="text-3xl font-extrabold text-indigo-700 mb-8">أسرع المتدربين</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 max-w-6xl mx-auto">
                @forelse($topUsers ?? [] as $user)
                    <div class="bg-indigo-50 rounded-2xl p-6 shadow hover:shadow-lg hover:scale-105 transition transform">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366F1&color=fff"
                             class="w-16 h-16 mx-auto rounded-full mb-3">
                        <h4 class="font-bold text-indigo-700">{{ $user->name }}</h4>
                        <p class="text-gray-600 text-sm">
                            متوسط السرعة:
                            <span class="font-semibold text-indigo-600">{{ round($user->average_wpm, 1) }}</span>
                            كلمة في الدقيقة
                        </p>
                    </div>
                @empty
                    <p class="col-span-5 text-gray-500">لا توجد بيانات حالياً.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Articles -->
    <section id="articles" class="py-16 bg-gray-50 fade-in">
        <div class="container mx-auto text-center">
            <h3 class="text-3xl font-extrabold text-indigo-700 mb-8">أحدث المقالات</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse($articles ?? [] as $article)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-1 transition border border-indigo-100">
                        <div class="h-40 bg-gradient-to-r from-indigo-500 to-blue-500 flex items-center justify-center relative">
                            <h4 class="text-white text-2xl font-bold drop-shadow px-6 leading-snug">{{ $article->title }}</h4>
                        </div>
                        <div class="p-6 text-right">
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                {{ Str::limit(strip_tags($article->content), 120, '...') }}
                            </p>
                            <a href="{{ route('article.show', $article->id) }}"
                               class="inline-block mt-2 text-indigo-600 font-semibold hover:text-indigo-800 transition">
                                اقرأ المزيد →
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">لا توجد مقالات حالياً.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Games -->
    <section id="games" class="py-16 bg-indigo-50 fade-in">
        <div class="container mx-auto text-center">
            <h3 class="text-3xl font-extrabold text-indigo-700 mb-8">الألعاب التعليمية</h3>
            <p class="text-gray-600 mb-10 max-w-2xl mx-auto">
                حسّن مهاراتك في الطباعة من خلال ألعاب تفاعلية ممتعة ومليئة بالتحديات!
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                @foreach ([
                    ['⚡', 'اختبار السرعة', 'اختبر سرعتك في الكتابة وتحدى نفسك لتحقيق رقم قياسي!', '/games'],
                    ['🏎️', 'سباق الطباعة', 'اكتب بسرعة لتقود سيارتك إلى خط النهاية قبل الآخرين!', '/games'],
                    ['🔠', 'لعبة الحروف', 'اضغط الحروف الصحيحة قبل أن تختفي لتزيد نقاطك بسرعة!', '/games'],
                    ['🎯', 'الكلمات العشوائية', 'اكتب أكبر عدد من الكلمات الصحيحة خلال 30 ثانية!', '/games']
                ] as [$icon, $title, $desc, $link])
                <div class="bg-white rounded-2xl p-6 shadow hover:shadow-xl hover:-translate-y-1 transition border border-indigo-100">
                    <div class="text-4xl mb-4 text-indigo-600">{{ $icon }}</div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $title }}</h4>
                    <p class="text-gray-600 text-sm mb-4">{{ $desc }}</p>
                    <a href="{{ url($link) }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                        ابدأ الآن
                    </a>
                </div>
                @endforeach
            </div>

            <div class="mt-10">
                <a href="{{ url('/games') }}"
                   class="inline-block bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 
                          text-white font-bold px-8 py-3 rounded-xl shadow-md transition">
                    عرض كل الألعاب
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
   
    <section id="rating" class="py-16 bg-white fade-in">
        <div class="container mx-auto text-center">
            <h3 class="text-3xl font-extrabold text-indigo-700 mb-6">ما رأيك في التطبيق؟</h3>
            <p class="text-gray-600 mb-4">قيم تجربتك وساعدنا على تحسين النسخة القادمة 👇</p>

            <div class="rating-container">
                <div class="stars" id="ratingStars">
                    <i class="fa-solid fa-star" data-value="1"></i>
                    <i class="fa-solid fa-star" data-value="2"></i>
                    <i class="fa-solid fa-star" data-value="3"></i>
                    <i class="fa-solid fa-star" data-value="4"></i>
                    <i class="fa-solid fa-star" data-value="5"></i>
                </div>
                <p id="ratingMsg" class="mt-3 text-sm text-gray-600"></p>
            </div>
        </div>
    </section>

  
  <x-secure-footer />


    <script src="https://cdn.tailwindcss.com"></script>
    <script>
       
        const stars = document.querySelectorAll('#ratingStars i');
        const msg = document.getElementById('ratingMsg');
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.getAttribute('data-value');
                stars.forEach(s => s.classList.remove('active'));
                for (let i = 0; i < value; i++) stars[i].classList.add('active');
                msg.textContent = `شكراً لتقييمك! (${value} / 5 ⭐)`;
                msg.classList.add('text-indigo-600', 'font-semibold');
            });
        });
    </script>
</body>
</html>
