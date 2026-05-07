@props(['href' => '#'])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'flex items-center w-full px-4 py-2.5 text-sm text-surface-600 dark:text-surface-400 hover:text-surface-900 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-all duration-150']) }}>
    {{ $slot }}
</a>
