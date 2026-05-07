@props(['type' => 'button', 'size' => 'md', 'loading' => false, 'icon' => null, 'disabled' => false, 'trailing' => false])

@php
    $sizeClasses = match ($size) {
        'xs' => 'px-2.5 py-1.5 text-xs gap-1.5',
        'sm' => 'px-3 py-2 text-sm gap-1.5',
        'md' => 'px-4 py-2.5 text-sm gap-2',
        'lg' => 'px-5 py-3 text-base gap-2',
        'xl' => 'px-6 py-3.5 text-base gap-2.5',
    };

    $baseClasses = 'relative inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-300 focus-ring disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.97]';
    $colorClasses = 'bg-white text-surface-700 border border-surface-300 hover:bg-surface-50 hover:border-surface-400 hover:text-surface-900 active:bg-surface-100 dark:bg-surface-800 dark:text-surface-300 dark:border-surface-600 dark:hover:bg-surface-700 dark:hover:border-surface-500 dark:hover:text-white dark:active:bg-surface-700';
    $shadowClasses = 'shadow-sm hover:shadow-md';
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $colorClasses . ' ' . $shadowClasses . ' ' . $sizeClasses]) }}
    @if($disabled || $loading) disabled @endif
>
    @if($loading)
        <svg class="animate-spin h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ $slot }}</span>
    @else
        @if($icon && !$trailing)
            <span class="flex-shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($icon && $trailing)
            <span class="flex-shrink-0">{!! $icon !!}</span>
        @endif
    @endif
</button>
