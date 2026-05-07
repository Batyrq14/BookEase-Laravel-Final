@props([
    'variant' => 'default',
    'hasDot'  => false,
])

@php
// ring-1 acts as the border — cleaner than border + border-color
$variants = [
    'default' => 'bg-surface-100  text-surface-600  ring-surface-200/80   dark:bg-ink-800     dark:text-ink-300   dark:ring-ink-700/80',
    'brand'   => 'bg-brand-50     text-brand-700    ring-brand-200/80     dark:bg-brand-950/50 dark:text-brand-300  dark:ring-brand-800/80',
    'blue'    => 'bg-blue-50      text-blue-700     ring-blue-200/80      dark:bg-blue-950/50  dark:text-blue-300   dark:ring-blue-800/80',
    'emerald' => 'bg-emerald-50   text-emerald-700  ring-emerald-200/80   dark:bg-emerald-950/50 dark:text-emerald-300 dark:ring-emerald-800/80',
    'red'     => 'bg-red-50       text-red-700      ring-red-200/80       dark:bg-red-950/50   dark:text-red-300    dark:ring-red-800/80',
    'amber'   => 'bg-accent-50    text-accent-700   ring-accent-200/80    dark:bg-amber-950/50 dark:text-accent-300  dark:ring-amber-800/80',
    'purple'  => 'bg-purple-50    text-purple-700   ring-purple-200/80    dark:bg-purple-950/50 dark:text-purple-300 dark:ring-purple-800/80',
    'teal'    => 'bg-teal-50      text-teal-700     ring-teal-200/80      dark:bg-teal-950/50  dark:text-teal-300   dark:ring-teal-800/80',
    'rose'    => 'bg-rose-50      text-rose-700     ring-rose-200/80      dark:bg-rose-950/50  dark:text-rose-300   dark:ring-rose-800/80',
];

$dotColors = [
    'default' => 'bg-surface-400 dark:bg-ink-400',
    'brand'   => 'bg-brand-500',
    'blue'    => 'bg-blue-500',
    'emerald' => 'bg-emerald-500',
    'red'     => 'bg-red-500',
    'amber'   => 'bg-accent-500',
    'purple'  => 'bg-purple-500',
    'teal'    => 'bg-teal-500',
    'rose'    => 'bg-rose-500',
];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold ring-1 ' . ($variants[$variant] ?? $variants['default'])
]) }}>
    @if($hasDot)
        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $dotColors[$variant] ?? $dotColors['default'] }}"></span>
    @endif
    {{ $slot }}
</span>
