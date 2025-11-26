@extends('layouts.admin')
@section('title', '📰 إدارة المقالات')

@section('content')
<div class="space-y-6" dir="rtl">

    <!-- Page title and add button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
            <i data-lucide="newspaper" class="w-6 h-6 text-indigo-500"></i>
            إدارة المقالات
        </h2>
        <a href="{{ route('admin.articles.create') }}" 
           class="bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 
                  text-white font-semibold px-5 py-2 rounded-xl shadow-sm transition-all">
            <i data-lucide="plus-circle" class="w-5 h-5 inline-block align-middle"></i>
            <span class="align-middle">إضافة مقال جديد</span>
        </a>
    </div>

    <!-- Success alert -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-center font-semibold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Articles table -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-5 py-3 font-semibold flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5"></i>
            قائمة المقالات
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead class="bg-indigo-100 dark:bg-gray-700 text-indigo-800 dark:text-indigo-200">
                    <tr>
                        <th class="p-3 font-semibold">#</th>
                        <th class="p-3 font-semibold">العنوان</th>
                        <th class="p-3 font-semibold">تاريخ الإنشاء</th>
                        <th class="p-3 font-semibold">التحكم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($articles as $article)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-gray-800 transition">
                            <td class="p-3 text-gray-500 font-medium">{{ $loop->iteration }}</td>
                            <td class="p-3 font-semibold text-gray-800 dark:text-gray-100">{{ $article->title }}</td>

                            <!-- Safe date handling -->
                            <td class="p-3 text-gray-500">
                                {{ optional($article->created_at)->format('Y-m-d') ?? '—' }}
                            </td>

                            <!-- Edit and delete actions -->
                            <td class="p-3 flex justify-center gap-2">
                                <a href="{{ route('admin.articles.edit', $article->id) }}"
                                   class="bg-yellow-400/80 hover:bg-yellow-500 text-gray-900 font-bold px-4 py-1.5 rounded-full text-sm transition">
                                   ✏️ تعديل
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المقال؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500/80 hover:bg-red-600 text-white font-bold px-4 py-1.5 rounded-full text-sm transition">
                                        🗑️ حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty state -->
                        <tr>
                            <td colspan="4" class="py-6 text-gray-500 font-semibold text-center">
                                لا توجد مقالات حالياً 📭
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 text-center bg-gray-50 dark:bg-gray-900">
            {{ $articles->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Lucide icons -->
<script> lucide.createIcons(); </script>
@endsection
