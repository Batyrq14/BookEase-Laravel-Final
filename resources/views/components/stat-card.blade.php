@props([
    'label' => '',
    'value' => '',
    'icon' => null,
    'color' => 'brand',
    'href' => null,
    'trend' => null,
    'subtitle' => null,
])

@php
$colorMap = [
    'brand'   => 'bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 ring-brand-500/10 dark:ring-brand-400/10',
    'blue'    => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-blue-500/10 dark:ring-blue-400/10',
    'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-emerald-500/10 dark:ring-emerald-400/10',
    'purple'  => 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 ring-purple-500/10 dark:ring-purple-400/10',
];

$bgDotClass = match ($color) {
    'blue'    => 'bg-blue-500/10 dark:bg-blue-400/10',
    'emerald' => 'bg-emerald-500/10 dark:bg-emerald-400/10',
    'purple'  => 'bg-purple-500/10 dark:bg-purple-400/10',
    default   => 'bg-brand-500/10 dark:bg-brand-400/10',
};

$iconColors = $colorMap[$color] ?? $colorMap['brand'];

$baseTag = $href ? 'a' : 'div';
$hoverClasses = $href ? 'cursor-pointer hover:shadow-md hover:border-gray-300 dark:hover:border-gray-500' : 'hover:shadow-md';
@endphp

<{{ $baseTag }} @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm transition-all duration-300 ' . $hoverClasses]) }}
>
    {{-- Background glow dot --}}
    <div class="absolute top-0 right-0 w-20 h-20 -mr-4 -mt-4 rounded-full {{ $bgDotClass }}"></div>

    <div class="relative">
        {{-- Icon --}}
        @if($icon)
        <div class="w-9 h-9 rounded-xl {{ $iconColors }} flex items-center justify-center mb-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
            </svg>
        </div>
        @endif

        {{-- Label --}}
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">{{ $label }}</p>

        {{-- Value + Trend --}}
        <div class="flex items-baseline gap-2 mt-1.5">
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $value }}</p>
            @if($trend)
            <span class="text-xs font-semibold {{ str_starts_with($trend, '+') ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                {{ $trend }}
            </span>
            @endif
        </div>

        {{-- Subtitle --}}
        @if($subtitle)
        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>
</{{ $baseTag }}>
