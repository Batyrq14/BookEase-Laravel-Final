@props([
    'type'     => 'button',
    'size'     => 'md',
    'loading'  => false,
    'icon'     => null,
    'trailing' => false,
    'disabled' => false,
    'href'     => null,
])

@php
    $sizeClasses = match ($size) {
        'xs' => 'px-2.5 py-1.5 text-xs  gap-1.5',
        'sm' => 'px-3   py-2   text-sm  gap-1.5',
        'md' => 'px-4   py-2.5 text-sm  gap-2',
        'lg' => 'px-5   py-3   text-base gap-2',
        'xl' => 'px-6   py-3.5 text-base gap-2.5',
    };

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if(!$href) type="{{ $type }}" @endif
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => trim(implode(' ', [
            // Light Ghost Button — pill shape, white bg, shadow-as-border
            'relative inline-flex items-center justify-center',
            'font-semibold rounded-full',
            'transition-all duration-150',
            'outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-ink-950',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            'bg-white dark:bg-ink-800 text-surface-900 dark:text-surface-200',
            'shadow-btn-ghost dark:shadow-[0_0_0_1px_rgba(255,255,255,0.08)]',
            'hover:shadow-card dark:hover:shadow-[0_0_0_1px_rgba(255,255,255,0.12)]',
            'active:translate-y-px active:bg-surface-50 dark:active:bg-ink-700',
            $sizeClasses,
        ]))
    ]) }}
    style="letter-spacing: -0.016em;"
    @if((!$href) && ($disabled || $loading)) disabled @endif
>
    @if($loading)
        <svg class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
        </svg>
        <span>{{ $slot }}</span>
    @else
        @if($icon && !$trailing)<span class="flex-shrink-0">{!! $icon !!}</span>@endif
        <span>{{ $slot }}</span>
        @if($icon && $trailing)<span class="flex-shrink-0">{!! $icon !!}</span>@endif
    @endif
</{{ $tag }}>
