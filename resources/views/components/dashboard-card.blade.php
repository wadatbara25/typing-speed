@props([
    'title' => 'العنوان',
    'value' => '—',
    'icon'  => 'activity',
    'color' => 'indigo',
])

@php
    $gradients = [
        'indigo'  => 'from-indigo-500 via-blue-500 to-indigo-600',
        'blue'    => 'from-blue-500 via-sky-500 to-blue-600',
        'emerald' => 'from-emerald-500 via-teal-400 to-emerald-600',
        'orange'  => 'from-orange-400 via-amber-500 to-orange-600',
        'violet'  => 'from-violet-500 via-purple-500 to-violet-600',
        'rose'    => 'from-rose-500 via-pink-500 to-rose-600',
        'gray'    => 'from-gray-400 via-gray-500 to-gray-600',
    ];

    $gradient = $gradients[$color] ?? $gradients['indigo'];
@endphp

<div 
    class="relative bg-white/95 dark:bg-gray-800 rounded-2xl p-6 shadow-lg 
           hover:shadow-xl transition duration-300 hover:-translate-y-1 
           overflow-hidden border border-gray-100 dark:border-gray-700 
           backdrop-blur-sm select-none">

    <div class="absolute inset-0 bg-gradient-to-r {{ $gradient }} opacity-10 dark:opacity-20"></div>

    <div class="relative flex justify-between items-center">
        <div>
            <h4 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">
                {{ $title }}
            </h4>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">
                {{ $value }}
            </p>
        </div>

        <div class="p-3 rounded-full bg-gradient-to-br {{ $gradient }} text-white shadow-md">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
    </div>
</div>

@once
    <script> document.addEventListener('DOMContentLoaded', () => lucide.createIcons()); </script>
@endonce
