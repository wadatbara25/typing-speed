@extends('layouts.admin')
@section('title', '➕ إضافة مقال جديد')

@section('content')
<div class="space-y-8" dir="rtl">

    <!-- Page header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-6 h-6 text-indigo-500"></i>
            إضافة مقال جديد
        </h2>
        <a href="{{ route('admin.articles.index') }}"
           class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 
                  font-semibold px-5 py-2 rounded-xl shadow-sm transition">
            ↩️ رجوع
        </a>
    </div>

    <!-- Article form -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">عنوان المقال</label>
                <input type="text" name="title"
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Summary -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">الملخص</label>
                <input type="text" name="summary"
                       value="{{ old('summary') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            </div>

            <!-- Content -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">محتوى المقال</label>
                <textarea name="content" rows="8"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Author -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">اسم الكاتب</label>
                <input type="text" name="author_name"
                       value="{{ old('author_name') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            </div>

            <!-- Image upload -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">صورة المقال</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-3 py-2 border rounded-lg cursor-pointer text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-1">حالة المقال</label>
                <select name="status"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>منشور</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 
                               text-white font-semibold px-6 py-2 rounded-xl shadow-md transition">
                    💾 حفظ المقال
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Lucide icons -->
<script> lucide.createIcons(); </script>
@endsection
