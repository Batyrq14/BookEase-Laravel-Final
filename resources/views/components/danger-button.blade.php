<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5
                bg-red-600 hover:bg-red-700 active:bg-red-800
                text-white text-sm font-semibold rounded-xl
                shadow-sm shadow-red-600/20 transition-all duration-150
                focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:ring-offset-2
                disabled:opacity-50 disabled:cursor-not-allowed',
]) }}>
    {{ $slot }}
</button>
