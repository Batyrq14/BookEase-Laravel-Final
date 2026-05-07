@props([
    'disabled'     => false,
    'error'        => false,
    'icon'         => null,
    'iconPosition' => 'left',
])

@php
    // Shadow replaces border — gives a recessed "well" feel on light,
    // a deep inset on dark. Focus expands the outer ring outward.
    $shadowClass = $error
        ? 'shadow-input-error dark:shadow-input-error focus:shadow-input-error'
        : 'shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark';
@endphp

<div class="relative">
    @if($icon && $iconPosition === 'left')
        <span class="pointer-events-none absolute inset-y-0 left-0 pl-3.5 flex items-center text-surface-400 dark:text-ink-500">
            {!! $icon !!}
        </span>
    @endif

    <input
        @if($disabled) disabled @endif
        {!! $attributes->merge([
            'class' => trim(implode(' ', array_filter([
                'block w-full rounded-xl',
                'border-0 outline-none',
                'bg-surface-50 dark:bg-ink-900/80',
                'px-3.5 py-2.5 text-sm',
                'text-surface-900 dark:text-white',
                'placeholder-surface-400 dark:placeholder-ink-500',
                $shadowClass,
                'transition-shadow duration-150',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                ($icon && $iconPosition === 'left')  ? 'pl-10' : '',
                ($icon && $iconPosition === 'right') ? 'pr-10' : '',
            ])))
        ]) !!}
    >

    @if($icon && $iconPosition === 'right')
        <span class="pointer-events-none absolute inset-y-0 right-0 pr-3.5 flex items-center text-surface-400 dark:text-ink-500">
            {!! $icon !!}
        </span>
    @endif
</div>
