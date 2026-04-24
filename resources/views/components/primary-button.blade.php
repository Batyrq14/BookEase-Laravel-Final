<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5
                bg-slate-600 hover:bg-slate-700 active:bg-slate-800
                text-white text-sm font-semibold rounded-xl
                shadow-sm shadow-slate-600/20 transition-all duration-150
                focus:outline-none focus:ring-2 focus:ring-slate-500/40 focus:ring-offset-2
                disabled:opacity-50 disabled:cursor-not-allowed',
]) }}>
    {{ $slot }}
</button>
