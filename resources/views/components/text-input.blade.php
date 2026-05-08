@props([
    'disabled'     => false,
    'error'        => false,
    'icon'         => null,
    'iconPosition' => 'left',
])

@php
    // DESIGN.md: inputs use 0px radius — deliberate contrast against pill button/badge language
    $shadowClass = $error
        ? 'shadow-input-error dark:shadow-input-error focus:shadow-input-error'
        : 'shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark';
@endphp

<div class="relative">
    @if($icon && $iconPosition === 'left')
        <span class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center text-surface-400 dark:text-ink-500">
            {!! $icon !!}
        </span>
    @endif

    <input
        @if($disabled) disabled @endif
        {!! $attributes->merge([
            'class' => trim(implode(' ', array_filter([
                // --radius-inputs: 0px, --tracking-body per DESIGN.md
                'block w-full rounded-none',
                'border-0 outline-none',
                'bg-white dark:bg-ink-900/80',
                'text-surface-900 dark:text-white',
                'placeholder-surface-400 dark:placeholder-ink-500',
                $shadowClass,
                'transition-shadow duration-150',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                ($icon && $iconPosition === 'left')  ? 'pl-12 pr-5' : '',
                ($icon && $iconPosition === 'right') ? 'pl-5 pr-12' : '',
                (!$icon) ? 'px-5' : '',
            ])))
        ]) !!}
        style="padding-top: 15px; padding-bottom: 15px; font-size: var(--text-body); letter-spacing: var(--tracking-body); color: var(--color-midnight-navy);"
    >

    @if($icon && $iconPosition === 'right')
        <span class="pointer-events-none absolute inset-y-0 right-0 pr-4 flex items-center text-surface-400 dark:text-ink-500">
            {!! $icon !!}
        </span>
    @endif
</div>
