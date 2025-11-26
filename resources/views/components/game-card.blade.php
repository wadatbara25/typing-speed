@props(['icon', 'color' => 'indigo', 'title', 'desc', 'type'])

@php
    // Define available color themes
    $colors = [
        'indigo'  => ['bg' => 'from-indigo-600 to-indigo-500', 'icon' => 'text-indigo-600', 'bgLight' => 'bg-indigo-100'],
        'blue'    => ['bg' => 'from-blue-600 to-blue-500', 'icon' => 'text-blue-600', 'bgLight' => 'bg-blue-100'],
        'green'   => ['bg' => 'from-green-600 to-green-500', 'icon' => 'text-green-600', 'bgLight' => 'bg-green-100'],
        'purple'  => ['bg' => 'from-purple-600 to-purple-500', 'icon' => 'text-purple-600', 'bgLight' => 'bg-purple-100'],
        'amber'   => ['bg' => 'from-amber-600 to-amber-500', 'icon' => 'text-amber-600', 'bgLight' => 'bg-amber-100'],
        'emerald' => ['bg' => 'from-emerald-600 to-emerald-500', 'icon' => 'text-emerald-600', 'bgLight' => 'bg-emerald-100'],
        'rose'    => ['bg' => 'from-rose-600 to-rose-500', 'icon' => 'text-rose-600', 'bgLight' => 'bg-rose-100'],
    ];
    $c = $colors[$color] ?? $colors['indigo'];
@endphp

{{-- Game Card Component --}}
<div onclick="openGame('{{ $type }}')"
     class="group block cursor-pointer bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-2xl 
            border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between text-center 
            transition-all duration-300 ease-out hover:-translate-y-2 
            hover:ring-4 hover:ring-{{ $color }}-200/50 dark:hover:ring-{{ $color }}-700/30
            hover:scale-[1.02] select-none relative overflow-hidden">

    {{-- Gradient overlay (appears on hover) --}}
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 
                bg-gradient-to-br {{ $c['bg'] }} blur-2xl rounded-2xl"></div>

    <div class="relative z-10">
        {{-- Game icon --}}
        <div class="flex justify-center mb-4">
            <div class="p-5 rounded-full {{ $c['bgLight'] }} dark:bg-opacity-30 
                        shadow-inner ring-2 ring-white/60 dark:ring-gray-600 transition-all duration-300 
                        group-hover:scale-110 group-hover:shadow-lg">
                <i data-lucide="{{ $icon }}" 
                   class="w-8 h-8 {{ $c['icon'] }} dark:text-{{ $color }}-300 transition-transform duration-300 group-hover:scale-110"></i>
            </div>
        </div>

        {{-- Title and description --}}
        <h3 class="text-xl font-bold {{ $c['icon'] }} dark:text-{{ $color }}-300 mb-2 drop-shadow-sm">
            {{ $title }}
        </h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
            {{ $desc }}
        </p>

        {{-- Start button --}}
        <div class="mt-6">
            <span 
                class="inline-block bg-gradient-to-r {{ $c['bg'] }} hover:brightness-110 
                       text-white font-semibold py-2 px-6 rounded-xl shadow-md 
                       group-hover:shadow-xl transition-all duration-300 ease-out transform group-hover:scale-105">
                ابدأ اللعبة
            </span>
        </div>
    </div>
</div>
