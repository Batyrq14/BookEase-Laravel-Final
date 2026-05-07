@props([
    'icon'          => null,
    'title'         => 'Nothing here yet',
    'description'   => null,
    'actionLabel'   => null,
    'actionUrl'     => null,
    'actionMethod'  => 'GET',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-20 px-8 text-center']) }}>

    {{-- Icon container with radial glow halo --}}
    <div class="relative mb-6">
        <div class="absolute inset-0 scale-[2.5] bg-radial-glow dark:bg-radial-glow-dark opacity-80 pointer-events-none"></div>
        <div class="relative w-14 h-14 rounded-2xl
                    bg-brand-50 dark:bg-brand-950/40
                    ring-1 ring-brand-200/60 dark:ring-brand-900/60
                    flex items-center justify-center">
            @if($icon)
                {{ $icon }}
            @else
                <svg class="w-6 h-6 text-brand-500 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            @endif
        </div>
    </div>

    <p class="font-display font-semibold text-[17px] text-surface-900 dark:text-white leading-snug">
        {{ $title }}
    </p>

    @if($description)
        <p class="mt-1.5 text-sm text-surface-500 dark:text-ink-400 max-w-xs leading-relaxed">
            {{ $description }}
        </p>
    @endif

    @if($actionLabel && $actionUrl)
        <div class="mt-6">
            @if($actionMethod === 'GET')
                <x-primary-button :href="$actionUrl" size="sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    {{ $actionLabel }}
                </x-primary-button>
            @else
                <form method="POST" action="{{ $actionUrl }}">
                    @csrf
                    <x-primary-button type="submit" size="sm">{{ $actionLabel }}</x-primary-button>
                </form>
            @endif
        </div>
    @endif

</div>
