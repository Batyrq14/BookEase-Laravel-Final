@props(['active' => false])

@php
    $classes = $active
        ? 'inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-semibold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-950/40 transition-all duration-150'
        : 'inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-medium text-surface-600 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white hover:bg-surface-100/70 dark:hover:bg-ink-800/70 transition-all duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
