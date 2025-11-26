@extends('layouts.app')
@section('title', '📘 دروس الطباعة — تطبيق الخوارزمي')

@section('content')
<div class="min-h-screen py-12 px-6"
     style="background: linear-gradient(135deg, #312e81, #3730a3, #4338ca, #2563eb);
            background-size: 400% 400%;
            animation: gradientShift 16s ease infinite;"
     dir="rtl">

    <style>
        /* Background animation */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Lesson card design */
        .lesson-card {
            position: relative;
            background: linear-gradient(145deg, #ffffff, #f3f4f6);
            border-radius: 20px;
            box-shadow:
                0 10px 20px rgba(0, 0, 0, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255,255,255,0.5);
            overflow: hidden;
            transition: all 0.35s ease;
        }

        .lesson-card::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle at 30% 30%, rgba(99,102,241,0.18), transparent 70%);
            pointer-events: none;
        }

        .lesson-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(59,130,246,0.35);
        }

        /* Start button style */
        .btn-start {
            background: linear-gradient(to right, #2563eb, #4338ca);
            box-shadow: 0 4px 15px rgba(59,130,246,0.4);
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            background: linear-gradient(to right, #1e3a8a, #312e81);
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(59,130,246,0.55);
        }
    </style>

    <!-- Page header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 text-white">
        <div>
            <h2 class="text-4xl font-extrabold mb-2 drop-shadow-xl">📘 دروس الطباعة</h2>
            <p class="text-indigo-100 text-sm font-medium tracking-wide">
                تدرب على الدروس وطور سرعة ودقة الطباعة لديك ✨
            </p>
        </div>
        <span class="text-indigo-200 text-sm mt-2 md:mt-0">
            عدد الدروس: {{ $lessons->count() }}
        </span>
    </div>

    <!-- Lessons grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">
        @forelse($lessons as $lesson)
            <div class="lesson-card p-6 backdrop-blur-xl bg-opacity-90 flex flex-col justify-between">

                <!-- Title & description -->
                <div class="relative z-10 mb-4">
                    <h3 class="text-xl font-extrabold text-indigo-700 mb-2">
                        {{ $lesson->title }}
                    </h3>
                    <p class="text-gray-700 text-sm leading-relaxed line-clamp-3">
                        {{ Str::limit($lesson->content, 100, '...') }}
                    </p>
                </div>

                <!-- Info bar -->
                <div class="relative z-10 flex justify-between text-xs md:text-sm text-gray-600
                            border-t border-gray-200 pt-3 mt-auto mb-4">
                    <span>⏱️ <strong>{{ $lesson->duration_limit ?? 'غير محددة' }}</strong> ث</span>
                    <span>🎯 <strong>{{ $lesson->level ?? '—' }}</strong></span>
                </div>

                <!-- Start button -->
                <button 
                    type="button"
                    onclick="window.location.href='{{ route('typing.show', $lesson->id) }}'"
                    class="btn-start w-full inline-flex items-center justify-center gap-2
                           px-6 py-2.5 text-base font-bold text-white rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300/40">
                    <i data-lucide="rocket" class="w-5 h-5"></i>
                    <span>ابدأ التمرين</span>
                </button>

            </div>
        @empty
            <!-- Empty state -->
            <p class="col-span-3 text-center text-white py-10 text-lg">
                لا توجد دروس متاحة حالياً.
            </p>
        @endforelse
    </div>
</div>

<!-- Initialize icons -->
<script>lucide.createIcons();</script>
@endsection
