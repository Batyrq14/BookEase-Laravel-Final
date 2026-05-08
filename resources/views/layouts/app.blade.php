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
<body class="font-sans antialiased bg-surface-50 dark:bg-ink-950 text-surface-900 dark:text-surface-100 min-h-screen">

    {{-- Skip to content --}}
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    {{-- ─── Sticky Navigation — Deep Cosmos (#001033) ────────────────────── --}}
    <nav x-data="{ open: false }" style="background-color: #001033;" class="sticky top-0 z-50">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Logo + Desktop Links --}}
                <div class="flex items-center gap-7">
                    <a href="{{ route('dashboard') }}" class="shrink-0">
                        <span class="font-sans font-semibold text-[15px] text-white" style="letter-spacing: -0.016em;">BookEase</span>
                    </a>

                    <div class="hidden md:flex items-center gap-0.5">
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }}">
                            Dashboard
                        </a>

                        @can('admin')
                        <a href="{{ route('services.index') }}"
                           class="{{ request()->routeIs('services.*') ? 'nav-link-active' : 'nav-link' }}">
                            Services
                        </a>
                        <a href="{{ route('admin.appointments.index') }}"
                           class="{{ request()->routeIs('admin.appointments.*') ? 'nav-link-active' : 'nav-link' }}">
                            Appointments
                        </a>
                        <a href="{{ route('categories.index') }}"
                           class="{{ request()->routeIs('categories.*') ? 'nav-link-active' : 'nav-link' }}">
                            Categories
                        </a>
                        <a href="{{ route('users.index') }}"
                           class="{{ (request()->routeIs('users.*') || request()->routeIs('providers.*')) ? 'nav-link-active' : 'nav-link' }}">
                            People
                        </a>
                        @endcan

                        @can('provider')
                        <a href="{{ route('provider.appointments.index') }}"
                           class="{{ request()->routeIs('provider.appointments.*') ? 'nav-link-active' : 'nav-link' }}">
                            My Schedule
                        </a>
                        <a href="{{ route('services.index') }}"
                           class="{{ request()->routeIs('services.*') ? 'nav-link-active' : 'nav-link' }}">
                            My Services
                        </a>
                        @endcan

                        @can('client')
                        <a href="{{ route('services.browse') }}"
                           class="{{ request()->routeIs('services.browse') ? 'nav-link-active' : 'nav-link' }}">
                            Browse Services
                        </a>
                        <a href="{{ route('appointments.index') }}"
                           class="{{ request()->routeIs('appointments.index') ? 'nav-link-active' : 'nav-link' }}">
                            My Appointments
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- Desktop Right: profile --}}
                <div class="hidden md:flex items-center gap-2">
                    @auth
                    <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-full transition-all duration-150 hover:bg-white/[0.08]"
                                style="border: 1px solid rgba(224,246,255,0.25);">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold text-surface-900"
                                 style="background-color: #d0f100;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-white/80 max-w-[110px] truncate" style="letter-spacing: -0.016em;">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-white/40 transition-transform duration-200"
                                 :class="profileOpen ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="profileOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             class="absolute right-0 mt-2 w-60 rounded-[20px] overflow-hidden bg-white dark:bg-ink-900 z-50"
                             style="box-shadow: rgba(0,39,80,0.08) 0px 8px 24px, rgba(0,39,80,0.04) 0px 0px 0px 1px;">

                            <div class="px-4 py-3.5 border-b border-black/[0.05] dark:border-white/[0.05] bg-surface-50 dark:bg-ink-800">
                                <p class="text-[10px] font-semibold uppercase text-surface-400 dark:text-ink-500" style="letter-spacing: 0.08em;">Signed in as</p>
                                <p class="text-sm font-semibold text-surface-900 dark:text-white mt-0.5 truncate" style="letter-spacing: -0.016em;">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1.5">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-600 dark:text-surface-300 hover:text-surface-900 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-ink-800 transition-colors duration-150"
                                   style="letter-spacing: -0.016em;">
                                    <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile settings
                                </a>
                            </div>

                            <div class="border-t border-black/[0.05] dark:border-white/[0.05] py-1.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-surface-500 dark:text-surface-400
                                                   hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                            style="letter-spacing: -0.016em;">
                                        <svg class="w-4 h-4 text-surface-400 dark:text-ink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="open = !open"
                        class="md:hidden p-2 rounded-full text-white/60 hover:text-white hover:bg-white/[0.08] transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             style="border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="px-4 py-3 space-y-0.5 max-w-[1200px] mx-auto">
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    Dashboard
                </a>
                @can('admin')
                <a href="{{ route('services.index') }}"
                   class="{{ request()->routeIs('services.*') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    Services
                </a>
                <a href="{{ route('admin.appointments.index') }}"
                   class="{{ request()->routeIs('admin.appointments.*') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    Appointments
                </a>
                <a href="{{ route('categories.index') }}"
                   class="{{ request()->routeIs('categories.*') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    Categories
                </a>
                <a href="{{ route('users.index') }}"
                   class="{{ (request()->routeIs('users.*') || request()->routeIs('providers.*')) ? 'nav-link-active' : 'nav-link' }} block w-full">
                    People
                </a>
                @endcan
                @can('provider')
                <a href="{{ route('provider.appointments.index') }}"
                   class="{{ request()->routeIs('provider.appointments.*') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    My Schedule
                </a>
                <a href="{{ route('services.index') }}"
                   class="{{ request()->routeIs('services.*') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    My Services
                </a>
                @endcan
                @can('client')
                <a href="{{ route('services.browse') }}"
                   class="{{ request()->routeIs('services.browse') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    Browse Services
                </a>
                <a href="{{ route('appointments.index') }}"
                   class="{{ request()->routeIs('appointments.index') ? 'nav-link-active' : 'nav-link' }} block w-full">
                    My Appointments
                </a>
                @endcan

            </div>

            @auth
            <div class="max-w-[1200px] mx-auto px-4 py-4" style="border-top: 1px solid rgba(255,255,255,0.08);">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-surface-900"
                         style="background-color: #d0f100;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white truncate" style="letter-spacing: -0.016em;">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-white/50 truncate" style="letter-spacing: -0.016em;">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="space-y-0.5">
                    <a href="{{ route('profile.edit') }}" class="nav-link block w-full">
                        Profile settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-full text-left text-red-400 hover:text-red-300 hover:bg-red-900/20">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    {{-- ─── Optional Page Header ───────────────────────────────────────────── --}}
    @isset($header)
    <header class="border-b border-black/[0.05] dark:border-white/[0.05] bg-white dark:bg-ink-900">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $header }}
        </div>
    </header>
    @endisset

    {{-- ─── Flash Messages ─────────────────────────────────────────────────── --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="alert-success flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="alert-error flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="alert-warning flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                {{ session('warning') }}
            </div>
            <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    {{-- ─── Main Content ────────────────────────────────────────────────────── --}}
    <main id="main-content" class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- ─── Back to Top ────────────────────────────────────────────────────── --}}
    <button x-data="{ visible: false }"
            x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 300 })"
            x-show="visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-6 z-40 p-3 rounded-full bg-white dark:bg-ink-900 text-surface-500 dark:text-ink-400 hover:text-surface-900 dark:hover:text-white transition-all duration-200"
            style="box-shadow: rgba(0,39,80,0.08) 0px 6px 16px -3px, rgba(0,39,80,0.04) 0px 0px 0px 1px;"
            aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
        </svg>
    </button>

    @stack('scripts')

</body>
</html>
