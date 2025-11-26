@extends('layouts.app')
@section('title', '👤 الملف الشخصي — تطبيق الخوارزمي')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6 space-y-12" dir="rtl">

    {{-- Header and avatar --}}
    <header class="text-center space-y-4">
        <div class="flex flex-col items-center space-y-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366F1&color=fff&size=128"
                 alt="Avatar"
                 class="rounded-full shadow-xl border-4 border-indigo-200 dark:border-indigo-600">

            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white drop-shadow-sm">
                {{ $user->name }}
            </h1>

            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->email }}</p>

            {{-- User level --}}
            @php
                $level = 'مبتدئ';
                $wpm = $stats['best_wpm'] ?? 0;
                if ($wpm >= 80) $level = 'محترف 🥇';
                elseif ($wpm >= 50) $level = 'متقدم 🥈';
                elseif ($wpm >= 30) $level = 'متوسط 🥉';
            @endphp

            <span class="inline-block mt-2 px-4 py-1.5 text-sm font-semibold rounded-full 
                         bg-gradient-to-r from-indigo-500 to-blue-500 text-white shadow-md">
                {{ $level }}
            </span>
        </div>
    </header>

    {{-- Stats cards --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center mt-10">
        @php
            $cards = [
                ['🏁', 'عدد المحاولات', $stats['attempts_count']],
                ['⚡', 'أفضل سرعة', $stats['best_wpm'] . ' ك/د'],
                ['🎯', 'أفضل دقة', $stats['best_accuracy'] . '%'],
                ['🚀', 'متوسط السرعة', $stats['avg_wpm'] . ' ك/د'],
                ['📈', 'متوسط الدقة', $stats['avg_accuracy'] . '%'],
                ['⌨️', 'إجمالي السرعات', $stats['total_wpm'] ?? 0],
            ];
        @endphp

        @foreach ($cards as [$icon, $title, $value])
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 
                        border border-indigo-100 dark:border-gray-700 rounded-3xl shadow-md hover:shadow-xl 
                        p-6 transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-4xl mb-3">{{ $icon }}</div>
                <h4 class="text-sm text-gray-500 dark:text-gray-400 mb-1 font-semibold">{{ $title }}</h4>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-indigo-300">{{ $value }}</p>
            </div>
        @endforeach
    </section>

    {{-- Update profile form --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl p-8 border border-gray-100 dark:border-gray-700 mt-12">
        <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-300 mb-6 text-center">
            ✏️ تعديل بيانات الحساب
        </h2>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl border border-green-300 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Update form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6 max-w-md mx-auto">
            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    الاسم الكامل
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full p-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl 
                              focus:ring-4 focus:ring-indigo-300 focus:border-indigo-400 
                              dark:bg-gray-900 dark:text-gray-100 outline-none transition">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    البريد الإلكتروني
                </label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full p-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl 
                              focus:ring-4 focus:ring-indigo-300 focus:border-indigo-400 
                              dark:bg-gray-900 dark:text-gray-100 outline-none transition">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Save button --}}
            <div class="flex justify-center pt-6">
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white 
                               font-bold rounded-xl shadow-md hover:shadow-lg hover:scale-105 
                               transition duration-300">
                    💾 حفظ التعديلات
                </button>
            </div>
        </form>

        {{-- Delete form --}}
        <div class="flex justify-center mt-6">
            <button id="deleteAccountBtn"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold 
                           rounded-xl shadow-md hover:shadow-lg transition duration-300">
                🗑️ حذف الحساب
            </button>
        </div>
    </div>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    // Confirm before delete
    const deleteBtn = document.getElementById('deleteAccountBtn');
    deleteBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف الحساب وجميع البيانات نهائيًا.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit delete form via fetch
                fetch("{{ route('profile.destroy') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        '_method': 'DELETE'
                    })
                }).then(() => {
                    Swal.fire({
                        title: 'تم الحذف',
                        text: 'تم حذف الحساب بنجاح.',
                        icon: 'success',
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        window.location.href = '/';
                    });
                });
            }
        });
    });
});
</script>
@endsection
