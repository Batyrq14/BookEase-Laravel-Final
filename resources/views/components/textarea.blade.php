@props([
    'disabled' => false,
    'error'    => false,
    'rows'     => 4,
])

@php
    $shadowClass = $error
        ? 'shadow-input-error dark:shadow-input-error focus:shadow-input-error'
        : 'shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark';
@endphp

<textarea
    rows="{{ $rows }}"
    @if($disabled) disabled @endif
    {!! $attributes->merge([
        'class' => trim(implode(' ', [
            'block w-full rounded-xl',
            'border-0 outline-none',
            'bg-surface-50 dark:bg-ink-900/80',
            'px-3.5 py-2.5 text-sm',
            'text-surface-900 dark:text-white',
            'placeholder-surface-400 dark:placeholder-ink-500',
            $shadowClass,
            'transition-shadow duration-150',
            'resize-y',
            'disabled:opacity-50 disabled:cursor-not-allowed',
        ]))
    ]) !!}
>{{ $slot }}</textarea>
