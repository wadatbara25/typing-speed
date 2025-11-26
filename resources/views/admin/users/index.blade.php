@extends('layouts.app')
@section('title', 'قائمة المستخدمين')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-indigo-700 mb-6">👥 المستخدمون</h1>

    <table class="w-full bg-white dark:bg-gray-800 rounded-lg shadow text-center border-collapse">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="p-3">#</th>
                <th class="p-3">الاسم</th>
                <th class="p-3">البريد الإلكتروني</th>
                <th class="p-3">الدور</th>
                <th class="p-3">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="p-3">{{ $loop->iteration }}</td>
                <td class="p-3 font-semibold">{{ $user->name }}</td>
                <td class="p-3">{{ $user->email }}</td>
                <td class="p-3">{{ $user->role ?? 'user' }}</td>
                <td class="p-3 flex justify-center gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800">عرض</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('هل تريد حذف هذا المستخدم؟')" class="text-red-600 hover:text-red-800">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
