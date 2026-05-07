@props(['disabled' => false, 'error' => false, 'icon' => null, 'iconPosition' => 'left'])

@php
    $baseClasses = 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-surface-900 placeholder-surface-400 transition-all duration-200 focus-ring dark:bg-surface-800 dark:text-white dark:placeholder-surface-500';

    $borderClasses = $error
        ? 'border-red-300 focus:border-red-500 dark:border-red-500/50 dark:focus:border-red-400'
        : 'border-surface-300 hover:border-surface-400 focus:border-brand-500 dark:border-surface-600 dark:hover:border-surface-500 dark:focus:border-brand-400';

    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed bg-surface-50 dark:bg-surface-800/50' : '';
@endphp

<div class="relative">
    @if($icon && $iconPosition === 'left')
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400 dark:text-surface-500">
            {!! $icon !!}
        </span>
    @endif

    <input
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge([
            'class' => $baseClasses . ' ' . $borderClasses . ' ' . $disabledClasses .
                       ($icon && $iconPosition === 'left' ? ' pl-10' : '') .
                       ($icon && $iconPosition === 'right' ? ' pr-10' : '')
        ]) !!}
    >

    @if($icon && $iconPosition === 'right')
        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-surface-400 dark:text-surface-500">
            {!! $icon !!}
        </span>
    @endif
</div>
