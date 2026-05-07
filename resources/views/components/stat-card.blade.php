@props([
    'label'    => '',
    'value'    => '',
    'icon'     => null,
    'color'    => 'brand',
    'href'     => null,
    'trend'    => null,
    'subtitle' => null,
])

@php
$iconClasses = match ($color) {
    'emerald' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 ring-emerald-200/60 dark:ring-emerald-900/60',
    'amber'   => 'bg-accent-50  dark:bg-amber-950/40   text-accent-600  dark:text-accent-400  ring-accent-200/60  dark:ring-amber-900/60',
    'red'     => 'bg-red-50     dark:bg-red-950/40     text-red-600     dark:text-red-400     ring-red-200/60     dark:ring-red-900/60',
    'sky'     => 'bg-sky-50     dark:bg-sky-950/40     text-sky-600     dark:text-sky-400     ring-sky-200/60     dark:ring-sky-900/60',
    default   => 'bg-brand-50   dark:bg-brand-950/40   text-brand-600   dark:text-brand-400   ring-brand-200/60   dark:ring-brand-900/60',
};

$tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => trim(implode(' ', [
            'relative overflow-hidden rounded-xl p-5',
            'bg-white dark:bg-ink-900',
            'shadow-card dark:shadow-card-dark',
            'transition-all duration-200',
            $href ? 'hover:-translate-y-px hover:shadow-card-hover dark:hover:shadow-card-dark-hover cursor-pointer' : '',
        ]))
    ]) }}>

    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-surface-400 dark:text-ink-500">
                {{ $label }}
            </p>
            <div class="flex items-baseline gap-2 mt-2">
                <p class="font-display text-3xl font-bold text-surface-900 dark:text-white tabular-nums">
                    {{ $value }}
                </p>
                @if($trend)
                    <span @class([
                        'text-xs font-semibold tabular-nums',
                        'text-emerald-600 dark:text-emerald-400' => str_starts_with((string)$trend, '+'),
                        'text-red-500    dark:text-red-400'      => str_starts_with((string)$trend, '-'),
                        'text-surface-500 dark:text-ink-400'     => !str_starts_with((string)$trend, '+') && !str_starts_with((string)$trend, '-'),
                    ])>{{ $trend }}</span>
                @endif
            </div>
            @if($subtitle)
                <p class="mt-1 text-xs text-surface-500 dark:text-ink-400">{{ $subtitle }}</p>
            @endif
        </div>

        @if($icon)
            <div class="flex-shrink-0 w-10 h-10 rounded-xl ring-1 flex items-center justify-center {{ $iconClasses }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
            </div>
        @endif
    </div>

</{{ $tag }}>
