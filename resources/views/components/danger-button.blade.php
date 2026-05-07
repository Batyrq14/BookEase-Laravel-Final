@props(['type' => 'submit', 'size' => 'md', 'loading' => false, 'icon' => null, 'disabled' => false, 'trailing' => false])

@php
    $sizeClasses = match ($size) {
        'xs' => 'px-2.5 py-1.5 text-xs gap-1.5',
        'sm' => 'px-3 py-2 text-sm gap-1.5',
        'md' => 'px-4 py-2.5 text-sm gap-2',
        'lg' => 'px-5 py-3 text-base gap-2',
        'xl' => 'px-6 py-3.5 text-base gap-2.5',
    };

    $baseClasses = 'relative inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-300 focus-ring disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.97] overflow-hidden';
    $colorClasses = 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 dark:bg-red-500 dark:hover:bg-red-600 dark:active:bg-red-700';
    $shadowClasses = 'shadow-md shadow-red-600/20 hover:shadow-lg hover:shadow-red-600/30 dark:shadow-red-500/20 dark:hover:shadow-red-500/30';
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $colorClasses . ' ' . $shadowClasses . ' ' . $sizeClasses]) }}
    @if($disabled || $loading) disabled @endif
>
    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none"></span>

    @if($loading)
        <svg class="animate-spin h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="relative z-10">{{ $slot }}</span>
    @else
        @if($icon && !$trailing)
            <span class="flex-shrink-0 relative z-10">{!! $icon !!}</span>
        @endif
        <span class="relative z-10">{{ $slot }}</span>
        @if($icon && $trailing)
            <span class="flex-shrink-0 relative z-10">{!! $icon !!}</span>
        @endif
    @endif
</button>
