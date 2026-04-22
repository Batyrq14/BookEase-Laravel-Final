@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'block w-full bg-white border border-slate-300 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 disabled:opacity-50 disabled:bg-slate-50 disabled:cursor-not-allowed']) }}
>
