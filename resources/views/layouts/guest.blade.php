<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'BookEase') }}</title>

    {{-- Fraunces (ivarTextFont substitute) + Inter (abcdFont substitute) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Fraunces:ital,opsz,wght@0,9..144,400;1,9..144,400&display=swap" rel="stylesheet">

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
<body class="font-sans antialiased bg-surface-50 dark:bg-ink-950 text-surface-900 dark:text-surface-100 min-h-screen flex flex-col relative">

    {{-- Ghost canvas atmospheric accents --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-40 blur-3xl"
             style="background: radial-gradient(circle, rgba(0,80,248,0.08) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full opacity-30 blur-3xl"
             style="background: radial-gradient(circle, rgba(95,189,247,0.10) 0%, transparent 70%);"></div>
    </div>

    {{-- Skip to content --}}
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col items-center justify-center p-4 py-16 relative z-10">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="mb-8">
            <span class="font-sans font-semibold text-[17px] text-surface-900 dark:text-white" style="letter-spacing: -0.016em;">BookEase</span>
        </a>

        {{-- Auth Card — Pure Surface elevated over Ghost Canvas --}}
        <div id="main-content"
             class="w-full max-w-md bg-white dark:bg-ink-900 rounded-[20px] p-8 animate-fade-in relative z-10"
             style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px, rgba(0,39,80,0.03) 0px 32px 32px -16px;">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <p class="mt-6 text-xs text-surface-400 dark:text-ink-500 text-center" style="letter-spacing: -0.016em;">
            &copy; {{ date('Y') }} BookEase
            &middot;
            <a href="{{ url('/') }}" class="hover:text-surface-900 dark:hover:text-white transition-colors">Home</a>
        </p>
    </div>

    @stack('scripts')

</body>
</html>
