@extends('layouts.app')
@section('title', 'لوحة التحكم — تطبيق الخوارزمي')

@section('content')
<div class="space-y-10 p-6" dir="rtl">

    <!-- Welcome section -->
    <section class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-indigo-700 font-[Tajawal]">
                👋 أهلاً {{ Auth::user()->name }}
            </h1>
            <p class="text-gray-600 mt-1 font-[Cairo]">
                استمر في التدريب ورفع مهارتك في الطباعة السريعة!
            </p>
        </div>

        <div class="text-sm text-gray-500 font-[Cairo]">
            آخر دخول:
            <span class="font-semibold text-indigo-600">
                {{ Auth::user()->last_login_at?->format('Y-m-d H:i') ?? '—' }}
            </span>
        </div>
    </section>

    <!-- General statistics -->
    <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach([
            ['📘', 'عدد الدروس', $lessonsCount ?? 0, 'text-blue-600'],
            ['⌨️', 'عدد المحاولات', $attemptsCount ?? 0, 'text-emerald-600'],
            ['⚡', 'أفضل سرعة', ($bestSpeed ?? 0).' كلمة/دقيقة', 'text-orange-500'],
            ['🎯', 'أفضل دقة', ($bestAccuracy ?? 0).'%', 'text-violet-600'],
        ] as [$icon, $label, $value, $color])
        <div class="bg-white rounded-xl shadow p-5 text-center border border-gray-100 transition hover:shadow-md hover:-translate-y-1 duration-300">
            <div class="text-3xl mb-1">{{ $icon }}</div>
            <h3 class="text-gray-500 text-sm font-[Cairo]">{{ $label }}</h3>
            <div class="text-2xl font-bold {{ $color }} mt-1 font-[Tajawal]">{{ $value }}</div>
        </div>
        @endforeach
    </section>

    <!-- Recent attempts -->
    <section class="bg-white rounded-xl shadow p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-[Tajawal]">
            🕒 آخر محاولاتك
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse text-sm md:text-base font-[Cairo]">
                <thead class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
                    <tr>
                        <th class="p-3 font-semibold">الدرس</th>
                        <th class="p-3 font-semibold">السرعة</th>
                        <th class="p-3 font-semibold">الدقة</th>
                        <th class="p-3 font-semibold">المدة (ث)</th>
                        <th class="p-3 font-semibold">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($recentAttempts as $attempt)
                        <tr class="border-b border-gray-100 hover:bg-indigo-50 transition">
                            <td class="p-3 font-semibold">{{ $attempt->lesson->title ?? '—' }}</td>
                            <td class="p-3 text-blue-600 font-semibold">{{ $attempt->wpm ?? 0 }} كلمة/دقيقة</td>
                            <td class="p-3 text-green-600 font-semibold">{{ $attempt->accuracy ?? 0 }}%</td>
                            <td class="p-3 text-gray-600">{{ $attempt->duration_seconds ?? '—' }}</td>
                            <td class="p-3 text-gray-500">{{ $attempt->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-gray-400">لم تقم بأي محاولة بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Top users -->
    <section class="bg-white rounded-xl shadow p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-6 font-[Tajawal]">
            🏆 أسرع 5 متدربين
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @forelse($topUsers as $user)
                <div class="bg-indigo-50 p-4 rounded-xl text-center hover:scale-105 transition shadow-sm">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366F1&color=fff"
                         class="w-14 h-14 mx-auto rounded-full mb-2 shadow">
                    <h4 class="font-semibold text-indigo-700 font-[Tajawal]">{{ $user->name }}</h4>
                   <p class="text-sm text-gray-600 mt-1 font-[Cairo]">
    {{ round($user->best_wpm ?? 0, 1) }} كلمة/دقيقة
</p>

                </div>
            @empty
                <p class="col-span-5 text-gray-500 text-center font-[Cairo]">لا يوجد متصدرون حالياً.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
