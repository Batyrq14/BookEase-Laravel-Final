@props([
    'title'   => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden']) }}>

    @if($title)
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
    </div>
    @endif

    @isset($header)
    <div class="px-6 py-4 border-b border-slate-100">
        {{ $header }}
    </div>
    @endisset

    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>

    @isset($footer)
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        {{ $footer }}
    </div>
    @endisset

</div>
