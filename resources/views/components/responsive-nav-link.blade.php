@props(['active' => false])

@php
    $classes = $active
        ? 'block w-full ps-3 pe-4 py-2.5 border-l-4 border-brand-600 dark:border-brand-400 text-start text-sm font-semibold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/20 focus:outline-none focus:text-brand-800 dark:focus:text-brand-200 focus:bg-brand-100 dark:focus:bg-brand-900/40 rounded-r-lg transition-all duration-150'
        : 'block w-full ps-3 pe-4 py-2.5 border-l-4 border-transparent text-start text-sm font-medium text-surface-600 dark:text-surface-400 hover:text-surface-800 dark:hover:text-surface-200 hover:bg-surface-50 dark:hover:bg-surface-700/50 hover:border-surface-300 dark:hover:border-surface-600 focus:outline-none focus:text-surface-800 dark:focus:text-surface-200 focus:bg-surface-50 dark:focus:bg-surface-700/50 focus:border-surface-300 dark:focus:border-surface-600 rounded-r-lg transition-all duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
