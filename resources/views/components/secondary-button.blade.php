<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5
                bg-white hover:bg-slate-50 border border-slate-300 hover:border-slate-400
                text-slate-700 hover:text-slate-900 text-sm font-semibold rounded-xl
                shadow-sm transition-all duration-150
                focus:outline-none focus:ring-2 focus:ring-slate-400/30 focus:ring-offset-2
                disabled:opacity-50 disabled:cursor-not-allowed',
]) }}>
    {{ $slot }}
</button>
