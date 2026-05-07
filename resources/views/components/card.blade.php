@props(['hover' => false, 'padding' => 'md', 'header' => null, 'footer' => null, 'gradient' => false])

@php
    $paddingClasses = match ($padding) {
        false, 'none' => '',
        'sm' => 'p-4',
        'md' => 'p-5 sm:p-6',
        'lg' => 'p-6 sm:p-8',
        'xl' => 'p-8 sm:p-10',
    };

    $hoverClasses = $hover
        ? 'transition-all duration-300 hover:-translate-y-1 hover:shadow-elevated cursor-pointer dark:hover:shadow-soft-lg'
        : '';

    $gradientClass = $gradient
        ? 'gradient-border'
        : '';
@endphp

<div {{ $attributes->merge(['class' => 'card ' . $paddingClasses . ' ' . $hoverClasses . ' ' . $gradientClass]) }}>
    @if($header)
        <div class="card-header -mx-5 sm:-mx-6">
            {{ $header }}
        </div>
    @endif
    {{ $slot }}
    @if($footer)
        <div class="card-footer -mx-5 sm:-mx-6">
            {{ $footer }}
        </div>
    @endif
</div>
