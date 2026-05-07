@props([
    'icon' => null,
    'title' => 'Nothing here yet',
    'description' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-20 text-center px-6']) }}>
    <div class="w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-6">
        @if($icon)
        {{ $icon }}
        @else
        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        @endif
    </div>
    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ $title }}</p>
    @if($description)
    <p class="text-sm text-gray-400 dark:text-gray-500 max-w-xs mb-6">{{ $description }}</p>
    @endif
    @if($actionLabel && $actionUrl)
    <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-5 py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ $actionLabel }}
    </a>
    @endif
</div>
