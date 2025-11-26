@extends('layouts.admin')
@section('title', 'لوحة إدارة النظام — تطبيق الخوارزمي')

@section('content')
<div class="p-6 space-y-10" dir="rtl">

    <!-- Page header -->
    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
                <i data-lucide="layout-dashboard" class="w-8 h-8 text-indigo-500"></i>
                لوحة إدارة النظام
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                إدارة المتدربين والمحتوى والإحصاءات العامة لتطبيق الخوارزمي.
            </p>
        </div>

        <span class="text-xs text-gray-400 dark:text-gray-500 font-medium select-none tracking-wide">
            AlKhawarizmi Admin Theme v2.2
        </span>
    </header>

    <!-- Stats cards -->
    @php
        $cards = [
            ['title' => 'عدد المستخدمين', 'value' => $usersCount, 'icon' => 'users', 'color' => 'indigo'],
            ['title' => 'عدد الدروس', 'value' => $lessonsCount, 'icon' => 'book-open', 'color' => 'blue'],
            ['title' => 'عدد المحاولات', 'value' => $attemptsCount, 'icon' => 'keyboard', 'color' => 'emerald'],
            ['title' => 'أفضل متدرب', 'value' => $bestUser?->name ?? '—', 'icon' => 'zap', 'color' => 'orange'],
        ];

        $gradients = [
            'indigo' => 'from-indigo-50 to-indigo-100 dark:from-gray-800 dark:to-gray-700',
            'blue' => 'from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-700',
            'emerald' => 'from-emerald-50 to-emerald-100 dark:from-gray-800 dark:to-gray-700',
            'orange' => 'from-orange-50 to-orange-100 dark:from-gray-800 dark:to-gray-700',
        ];
    @endphp

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($cards as $card)
            <div class="relative bg-gradient-to-br {{ $gradients[$card['color']] }} rounded-2xl p-6 shadow hover:shadow-xl transition-transform transform hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-gray-600 dark:text-gray-300 text-sm mb-1 font-medium">{{ $card['title'] }}</h4>
                        <p class="text-3xl font-extrabold text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-300">
                            {{ $card['value'] }}
                        </p>
                    </div>
                    <div class="bg-{{ $card['color'] }}-500/10 p-3 rounded-xl shadow-inner">
                        <i data-lucide="{{ $card['icon'] }}" class="w-8 h-8 text-{{ $card['color'] }}-500"></i>
                    </div>
                </div>

                <!-- Decorative line -->
                <span class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-{{ $card['color'] }}-400 to-{{ $card['color'] }}-600 rounded-b-2xl"></span>
            </div>
        @endforeach
    </section>

    <!-- Overview section -->
    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 hover:shadow-xl transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-indigo-500"></i>
                نظرة عامة
            </h3>
            <span class="text-sm text-gray-400">📊 لمحة سريعة</span>
        </div>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            توفر هذه اللوحة نظرة شاملة على أداء المستخدمين والنشاط العام في النظام.
            يمكنك من هنا إدارة الدروس والمتدربين ومتابعة نمو الأداء بشكل مبسط وواضح.
        </p>
    </section>

    <!-- Latest updates -->
    <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-4 flex items-center gap-2">
            <i data-lucide="calendar" class="w-5 h-5 text-indigo-500"></i>
            آخر التحديثات
        </h3>

        <ul class="space-y-3 text-gray-600 dark:text-gray-300 text-sm">
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-circle text-xs mt-1.5 text-indigo-500"></i>
                تمت إضافة دروس جديدة في لوحة المفاتيح العربية.
            </li>
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-circle text-xs mt-1.5 text-blue-500"></i>
                تحسين أداء صفحة التدريب وإضافة مقياس السرعة في الأعلى.
            </li>
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-circle text-xs mt-1.5 text-emerald-500"></i>
                تحديث نظام الدخول لتجربة أكثر أمانًا وثباتًا.
            </li>
        </ul>
    </section>

</div>

<script>lucide.createIcons();</script>
@endsection
