<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'BookEase') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    {{-- Dark mode initialization (prevents FOUC) --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-surface-50 dark:bg-surface-900 text-surface-900 dark:text-surface-100 min-h-screen flex flex-col relative">

    {{-- Background decorations --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-brand-400/10 dark:bg-brand-500/5 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-sky-400/10 dark:bg-sky-500/5 blur-3xl"></div>
    </div>

    {{-- Skip to content --}}
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    {{-- Dark mode toggle --}}
    <button onclick="toggleDark()" class="fixed top-4 right-4 z-50 p-2.5 rounded-xl bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-200 hover:bg-surface-50 dark:hover:bg-surface-700 shadow-sm hover:shadow-md transition-all duration-200" aria-label="Toggle dark mode">
        <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
        </svg>
        <svg class="w-4 h-4 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
        </svg>
    </button>

    {{-- Main Content (centered) --}}
    <div class="flex-1 flex flex-col items-center justify-center p-4 py-16 relative z-10">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="mb-8 relative z-10">
            <span class="font-display font-bold text-xl tracking-tight leading-none text-surface-900 dark:text-white">Book<span class="text-brand-600 dark:text-brand-400">Ease</span></span>
        </a>

        {{-- Main Card --}}
        <div id="main-content" class="w-full max-w-md glass-card dark:bg-surface-800 dark:border-surface-700 rounded-2xl shadow-soft-lg p-8 animate-fade-in relative z-10">
            {{ $slot }}
        </div>

        {{-- Footer link --}}
        <p class="mt-6 text-xs text-surface-400 dark:text-surface-500 text-center">
            &copy; {{ date('Y') }} BookEase
            &middot;
            <a href="{{ url('/') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Home</a>
        </p>
    </div>

    @stack('scripts')

    <script>
        function toggleDark() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</body>
</html>
