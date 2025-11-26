@extends('layouts.app')
@section('title', '➕ إضافة درس جديد — تطبيق الخوارزمي')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 space-y-6" dir="rtl">

    <!-- Page header -->
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
            ➕ إضافة درس جديد
        </h2>
        <a href="{{ route('admin.lessons.index') }}"
           class="text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-300 transition">
           ← الرجوع إلى قائمة الدروس
        </a>
    </div>

    <!-- Add lesson form -->
    <form id="lessonForm" action="{{ route('admin.lessons.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Lesson title -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                عنوان الدرس <span class="text-red-500">*</span>
            </label>
            <input name="title"
                   value="{{ old('title') }}"
                   class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100 transition"
                   required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lesson content -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                نص الدرس <span class="text-red-500">*</span>
            </label>
            <textarea name="content" rows="6"
                      class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100 transition"
                      required>{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Level and duration -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">المستوى</label>
                <input name="level"
                       value="{{ old('level') }}"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100 transition">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">المدة (بالثواني)</label>
                <input name="duration_limit" type="number" min="10"
                       value="{{ old('duration_limit') }}"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100 transition">
            </div>
        </div>

        <!-- Action buttons -->
        <div class="flex justify-between items-center pt-4">
            <a href="{{ route('admin.lessons.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold transition dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
               إلغاء
            </a>
            <button type="submit" id="saveBtn"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md transition flex items-center justify-center gap-2">
                💾 <span>حفظ الدرس</span>
            </button>
        </div>
    </form>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const saveBtn = document.getElementById('saveBtn');
    const form = document.getElementById('lessonForm');

    // Disable button and show spinner on submit
    form.addEventListener('submit', () => {
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg> جاري الحفظ...`;
    });

    // Success alert
    @if(session('success'))
        Swal.fire({
            title: '✅ تم الحفظ بنجاح!',
            html: `<p class="text-gray-700 text-lg">تمت إضافة الدرس إلى النظام بنجاح.</p>`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: '📚 عرض قائمة الدروس',
            cancelButtonText: '➕ إضافة درس آخر',
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('admin.lessons.index') }}";
            } else {
                window.location.reload();
            }
        });
    @elseif(session('error'))
        // Error alert
        Swal.fire({
            title: '⚠️ لم يتم الحفظ',
            html: `<p class="text-gray-700 text-lg">{{ session('error') }}</p>`,
            icon: 'error',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#ef4444',
        });
    @endif
});
</script>

<!-- Lucide icons -->
<script>lucide.createIcons();</script>
@endsection
