@props([
    'hover'   => false,
    'padding' => 'md',
    'header'  => null,
    'footer'  => null,
])

@php
    $paddingClasses = match ($padding) {
        false, 'none' => '',
        'sm'          => 'p-4',
        'md'          => 'p-5 sm:p-6',
        'lg'          => 'p-6 sm:p-8',
        'xl'          => 'p-8 sm:p-10',
    };

    $hoverClasses = $hover
        ? 'hover:-translate-y-px hover:shadow-card-hover dark:hover:shadow-card-dark-hover cursor-pointer'
        : '';
@endphp

<div {{ $attributes->merge([
    'class' => trim(implode(' ', array_filter([
        'bg-white dark:bg-ink-900',
        'rounded-xl',
        'shadow-card dark:shadow-card-dark',
        'transition-all duration-200',
        $paddingClasses,
        $hoverClasses,
    ]))),
]) }}>

    @if($header)
        <div class="px-5 sm:px-6 py-4
                    border-b border-black/[0.06] dark:border-white/[0.05]
                    -mx-5 sm:-mx-6 -mt-5 sm:-mt-6 mb-5 sm:mb-6
                    rounded-t-xl bg-surface-50/60 dark:bg-ink-800/60">
            {{ $header }}
        </div>
    @endif

    {{ $slot }}

    @if($footer)
        <div class="px-5 sm:px-6 py-4
                    border-t border-black/[0.06] dark:border-white/[0.05]
                    -mx-5 sm:-mx-6 -mb-5 sm:-mb-6 mt-5 sm:mt-6
                    rounded-b-xl bg-surface-50/60 dark:bg-ink-800/60">
            {{ $footer }}
        </div>
    @endif

</div>
