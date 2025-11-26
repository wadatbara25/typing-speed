@extends('layouts.admin')
@section('title', 'إدارة الدروس — تطبيق الخوارزمي')

@section('content')
<div class="p-6 space-y-8" dir="rtl">

    <!-- Page header with title and action button -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
                <i data-lucide="book-open" class="w-7 h-7 text-indigo-500"></i>
                إدارة الدروس
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">يمكنك هنا تعديل أو حذف أو إضافة دروس جديدة.</p>
        </div>
        <a href="{{ route('admin.lessons.create') }}"
           class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                  text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all duration-300 hover:scale-105">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> إضافة درس جديد
        </a>
    </div>

    <!-- Lessons table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <table class="w-full border-collapse text-center text-gray-700 dark:text-gray-200">
            <thead class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
                <tr>
                    <th class="p-3 text-sm md:text-base">#</th>
                    <th class="p-3 text-sm md:text-base">العنوان</th>
                    <th class="p-3 text-sm md:text-base">المستوى</th>
                    <th class="p-3 text-sm md:text-base">المدة</th>
                    <th class="p-3 text-sm md:text-base">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessons as $lesson)
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                        <td class="p-3 font-semibold">{{ $loop->iteration }}</td>
                        <td class="p-3 font-medium text-gray-900 dark:text-gray-100">{{ $lesson->title }}</td>
                        <td class="p-3">{{ $lesson->level ?? '—' }}</td>
                        <td class="p-3">{{ $lesson->duration_limit ?? '—' }} ث</td>
                        <td class="p-3">
                            <div class="flex justify-center items-center gap-3">
                                <!-- Edit action -->
                                <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                   class="flex items-center gap-1 text-blue-600 hover:text-blue-800 transition font-semibold">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i> تعديل
                                </a>

                                <!-- Delete action -->
                                <button type="button"
                                        class="flex items-center gap-1 text-red-600 hover:text-red-800 transition font-semibold delete-btn"
                                        data-id="{{ $lesson->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i> حذف
                                </button>

                                <form id="delete-form-{{ $lesson->id }}" method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-gray-500 dark:text-gray-400">لا توجد دروس حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        {{ $lessons->links() }}
    </div>
</div>

<!-- SweetAlert2 integration -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    // Delete confirmation dialog
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;

            Swal.fire({
                title: '⚠️ هل أنت متأكد؟',
                html: `<p class="text-gray-700 text-lg">سيتم حذف هذا الدرس نهائيًا من النظام.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '🗑️ نعم، احذف الدرس',
                cancelButtonText: '❌ إلغاء',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                backdrop: `
                    rgba(0,0,0,0.45)
                    url("https://i.gifer.com/VAyR.gif")
                    center top
                    no-repeat
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });

    // Success alert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'تم بنجاح 🎉',
            html: `<p class="text-gray-700 text-lg">{{ session('success') }}</p>`,
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#4f46e5',
        });
    @endif

    // Error alert
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'حدث خطأ ⚠️',
            html: `<p class="text-gray-700 text-lg">{{ session('error') }}</p>`,
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#ef4444',
        });
    @endif
});
</script>
@endsection
