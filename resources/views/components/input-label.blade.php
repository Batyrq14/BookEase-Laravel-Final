@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-surface-700 dark:text-surface-300 mb-1.5']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
    @endif
</label>
