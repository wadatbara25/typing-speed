@props(['value' => 0, 'highlight' => true])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1']) }}>
    <span class="{{ $highlight ? 'text-indigo-600 font-bold' : 'text-gray-700 dark:text-gray-200' }}">
        {{ round($value, 1) }}
    </span>
    <span class="text-sm text-gray-500 dark:text-gray-400">كلمة/دقيقة</span>
</span>
