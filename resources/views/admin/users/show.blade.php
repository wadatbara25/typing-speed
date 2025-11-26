@extends('layouts.app')
@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
    <h2 class="text-xl font-bold text-indigo-700 mb-4">👤 {{ $user->name }}</h2>
    <p class="text-gray-600 mb-2">📧 {{ $user->email }}</p>
    <p class="text-gray-600 mb-4">🎭 الدور: {{ $user->role ?? 'user' }}</p>

    <h3 class="text-lg font-bold text-indigo-600 mb-2">📋 محاولاته:</h3>
    <table class="w-full border-collapse text-center text-sm bg-white dark:bg-gray-800 rounded">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class="p-2">الدرس</th>
                <th class="p-2">WPM</th>
                <th class="p-2">الدقة</th>
                <th class="p-2">التاريخ</th>
            </tr>
        </thead>
        <tbody>
        @forelse($user->attempts as $attempt)
            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="p-2">{{ $attempt->lesson->title ?? '-' }}</td>
                <td class="p-2">{{ $attempt->wpm }}</td>
                <td class="p-2">{{ $attempt->accuracy }}%</td>
                <td class="p-2 text-gray-500">{{ $attempt->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="p-4 text-gray-400">لا توجد محاولات بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
