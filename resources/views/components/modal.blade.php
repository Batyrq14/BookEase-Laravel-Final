@props([
    'name',
    'show'     => false,
    'maxWidth' => '2xl',
])

@php
$maxWidthClass = [
    'sm'  => 'sm:max-w-sm',
    'md'  => 'sm:max-w-md',
    'lg'  => 'sm:max-w-lg',
    'xl'  => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
        },
        firstFocusable()     { return this.focusables()[0] },
        lastFocusable()      { return this.focusables().slice(-1)[0] },
        nextFocusable()      { return this.focusables()[this.nextFocusableIndex()]  || this.firstFocusable() },
        prevFocusable()      { return this.focusables()[this.prevFocusableIndex()]  || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 sm:p-0"
    style="display: {{ $show ? 'flex' : 'none' }};"
>
    {{-- Backdrop: 2px blur makes background feel spatial, not just covered --}}
    <div
        x-show="show"
        x-on:click="show = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-ink-950/60 backdrop-blur-[2px]"
        aria-hidden="true"
    ></div>

    {{-- Panel: spring entrance (fast in, soft settle) --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-[0.97]"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 sm:scale-[0.97]"
        class="relative z-10 w-full {{ $maxWidthClass }} sm:mx-auto overflow-hidden
               rounded-2xl
               bg-white dark:bg-ink-900
               shadow-[0_24px_64px_rgba(0,0,0,0.18),0_0_0_1px_rgba(0,0,0,0.05)]
               dark:shadow-[0_24px_64px_rgba(0,0,0,0.60),0_0_0_1px_rgba(255,255,255,0.06)]"
    >
        {{ $slot }}
    </div>
</div>
