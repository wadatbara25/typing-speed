@extends('layouts.app')

@section('title', 'المقالات | تطبيق الخوارزمي لتعلم الطباعة')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-100 py-12 px-6" dir="rtl">

    <!-- Page header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-3">📰 المقالات التعليمية</h1>
        <p class="text-gray-600 text-lg">تعرف على نصائح الخوارزمي لزيادة سرعة ودقة الطباعة بالعربية والإنجليزية.</p>
        <div class="w-24 h-1 bg-indigo-500 mx-auto mt-3 rounded-full"></div>
    </div>

    <!-- Articles grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
        @forelse($articles as $article)
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                
                <!-- Article cover -->
                @if($article->cover_image)
                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" class="h-48 w-full object-cover">
                @else
                    <div class="h-48 bg-gradient-to-r from-indigo-400 to-blue-400 flex items-center justify-center text-white text-6xl font-bold">
                        {{ mb_substr($article->title, 0, 1) }}
                    </div>
                @endif

                <!-- Article body -->
                <div class="p-6">
                    <h2 class="text-xl font-bold text-indigo-700 mb-2 leading-snug">{{ $article->title }}</h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        {{ Str::limit(strip_tags($article->summary ?? $article->content), 110, '...') }}
                    </p>

                    <!-- Article meta -->
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span><i class="fas fa-user ml-1 text-indigo-400"></i> {{ $article->author_name ?? 'فريق الخوارزمي' }}</span>
                        <span><i class="fas fa-calendar-alt ml-1 text-indigo-400"></i> {{ $article->created_at->format('Y-m-d') }}</span>
                    </div>

                    <!-- Read more button -->
                    <div class="mt-4 text-left">
                        <a href="{{ route('article.show', $article->id) }}"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                            اقرأ المزيد
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 text-lg col-span-3">لا توجد مقالات حالياً 📭</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
        {{ $articles->links('pagination::tailwind') }}
    </div>
</div>
@endsection
