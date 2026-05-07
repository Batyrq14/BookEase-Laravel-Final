@props([
    'variant' => 'default',
    'hasDot' => false,
])

@php
$variants = [
    'default'   => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
    'brand'     => 'bg-brand-50 text-brand-700 border-brand-200 dark:bg-brand-900/20 dark:text-brand-300 dark:border-brand-800',
    'blue'      => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800',
    'emerald'   => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-800',
    'red'       => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-800',
    'amber'     => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/20 dark:text-amber-300 dark:border-amber-800',
    'purple'    => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/20 dark:text-purple-300 dark:border-purple-800',
];

$dotColors = [
    'default'   => 'bg-gray-400',
    'brand'     => 'bg-brand-500',
    'blue'      => 'bg-blue-500',
    'emerald'   => 'bg-emerald-500',
    'red'       => 'bg-red-500',
    'amber'     => 'bg-amber-500',
    'purple'    => 'bg-purple-500',
];

$classes = $variants[$variant] ?? $variants['default'];
$dotClass = $dotColors[$variant] ?? $dotColors['default'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border ' . $classes]) }}>
    @if($hasDot)
    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotClass }}"></span>
    @endif
    {{ $slot }}
</span>
