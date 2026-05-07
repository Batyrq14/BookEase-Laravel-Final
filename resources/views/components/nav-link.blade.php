@props(['active' => false])

@php
    $classes = $active
        ? 'inline-flex items-center px-1 pt-1 text-sm font-semibold text-brand-600 dark:text-brand-400 border-b-2 border-brand-600 dark:border-brand-400'
        : 'inline-flex items-center px-1 pt-1 text-sm font-medium text-surface-600 dark:text-surface-300 hover:text-surface-900 dark:hover:text-white border-b-2 border-transparent hover:border-surface-300 dark:hover:border-surface-600 transition-all duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
