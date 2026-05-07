<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'BookEase') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
<body class="font-sans antialiased bg-surface-50 dark:bg-surface-900 text-surface-900 dark:text-surface-100 min-h-screen">

    {{-- Skip to content --}}
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    {{-- ─── Sticky Navigation ──────────────────────────────────────────────── --}}
    <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/80 dark:border-slate-700/80 shadow-sm dark:shadow-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo + Desktop Links --}}
                <div class="flex items-center gap-8">
                    <x-application-logo />

                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('dashboard') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Dashboard
                        </a>

                        @can('admin')
                        <a href="{{ route('services.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('services.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Services
                        </a>
                        <a href="{{ route('admin.appointments.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.appointments.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Appointments
                        </a>
                        <a href="{{ route('categories.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('categories.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Categories
                        </a>
                        <a href="{{ route('providers.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('providers.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Providers
                        </a>
                        <a href="{{ route('users.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('users.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Users
                        </a>
                        @endcan

                        @can('provider')
                        <a href="{{ route('provider.appointments.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('provider.appointments.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            My Schedule
                        </a>
                        <a href="{{ route('services.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('services.*') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            My Services
                        </a>
                        @endcan

                        @can('client')
                        <a href="{{ route('services.browse') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('services.browse') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            Browse Services
                        </a>
                        <a href="{{ route('appointments.index') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('appointments.index') ? 'text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50/50 dark:hover:bg-brand-900/20' }}">
                            My Appointments
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- Desktop Right Side: Dark Toggle + Profile Dropdown --}}
                <div class="hidden md:flex items-center gap-2">
                    {{-- Dark mode toggle --}}
                    <button onclick="toggleDark()"
                            class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-200"
                            aria-label="Toggle dark mode">
                        <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <svg class="w-4 h-4 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>

                    @auth
                    <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border transition-all duration-200
                                       bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-600 hover:border-brand-300 dark:hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-brand-900/20 hover:shadow-sm">
                            <div class="w-7 h-7 rounded-lg bg-brand-600 dark:bg-brand-500 flex items-center justify-center shadow-sm shadow-brand-500/25">
                                <span class="text-[11px] font-bold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 max-w-[120px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform duration-200"
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
                             class="absolute right-0 mt-2 w-64 rounded-2xl overflow-hidden
                                    bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-soft-lg z-50">

                            <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white mt-0.5 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1.5">
                                <x-dropdown-link :href="route('profile.edit')">
                                    <span class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profile settings
                                    </span>
                                </x-dropdown-link>
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-700 py-1.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-500 dark:text-slate-400
                                                   hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-150">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="md:hidden p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors duration-200">
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
             class="md:hidden border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="px-4 py-3 space-y-0.5">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                @can('admin')
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                    Services
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.*')">
                    Appointments
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    Categories
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('providers.index')" :active="request()->routeIs('providers.*')">
                    Providers
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    Users
                </x-responsive-nav-link>
                @endcan
                @can('provider')
                <x-responsive-nav-link :href="route('provider.appointments.index')" :active="request()->routeIs('provider.appointments.*')">
                    My Schedule
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                    My Services
                </x-responsive-nav-link>
                @endcan
                @can('client')
                <x-responsive-nav-link :href="route('services.browse')" :active="request()->routeIs('services.browse')">
                    Browse Services
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.index')">
                    My Appointments
                </x-responsive-nav-link>
                @endcan

                {{-- Mobile dark toggle --}}
                <button onclick="toggleDark()"
                        class="w-full text-left flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-150">
                    <span class="dark:hidden flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                        Dark Mode
                    </span>
                    <span class="hidden dark:flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        Light Mode
                    </span>
                </button>
            </div>

            @auth
            <div class="border-t border-slate-200 dark:border-slate-700 px-4 py-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-brand-600 dark:bg-brand-500 flex items-center justify-center shadow-sm shadow-brand-500/25">
                        <span class="text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="space-y-0.5">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors duration-150">
                        Profile settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150">
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
    <header class="border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $header }}
        </div>
    </header>
    @endisset

    {{-- ─── Flash Messages (with auto-dismiss) ─────────────────────────────── --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300 transition-colors">
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
         class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-medium shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors">
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
         class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-sm font-medium shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                {{ session('warning') }}
            </div>
            <button @click="show = false" class="text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    {{-- ─── Main Content ────────────────────────────────────────────────────── --}}
    <main id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- ─── Back to Top Button ──────────────────────────────────────────────── --}}
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
            class="fixed bottom-6 right-6 z-40 p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-300 dark:hover:border-brand-500 shadow-soft hover:shadow-soft-lg transition-all duration-300"
            aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
        </svg>
    </button>

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
