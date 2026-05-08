@props([
    'type'     => 'submit',
    'size'     => 'md',
    'loading'  => false,
    'icon'     => null,
    'trailing' => false,
    'disabled' => false,
    'href'     => null,
])

@php
    $sizeClasses = match ($size) {
        'xs' => 'px-3   py-1.5 text-[13px] gap-1.5',
        'sm' => 'px-4   py-2   text-sm     gap-1.5',
        'md' => 'px-5   py-2.5 text-[15px] gap-2',
        'lg' => 'px-6   py-3   text-[15px] gap-2',
        'xl' => 'px-7   py-3.5 text-[15px] gap-2.5',
    };

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if(!$href) type="{{ $type }}" @endif
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => trim(implode(' ', [
            // Dark Solid Button — pill shape, Deep Cosmos fill, near-white text
            'relative inline-flex items-center justify-center',
            'font-semibold rounded-full',
            'bg-brand-600 text-[#fafeff]',
            'shadow-btn hover:shadow-btn-hover',
            'hover:bg-brand-700',
            'active:translate-y-px active:shadow-btn-active active:bg-brand-800',
            'transition-all duration-150',
            'outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-ink-950',
            'disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-y-0',
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
