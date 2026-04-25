<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen">

    {{-- ─── Sticky Navigation ──────────────────────────────────────────────── --}}
    <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo + Desktop Links --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-slate-600 flex items-center justify-center shadow-md shadow-slate-600/20">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-[17px] font-bold tracking-tight text-slate-900">
                            Book<span class="text-slate-600">Ease</span>
                        </span>
                    </a>

                    <div class="hidden md:flex items-center gap-0.5">
                        <a href="{{ route('dashboard') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                                  {{ request()->routeIs('dashboard') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Dashboard
                        </a>

                        @can('admin')
                        <a href="{{ route('services.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                                  {{ request()->routeIs('services.*') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Services
                        </a>
                        <a href="{{ route('admin.appointments.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                                  {{ request()->routeIs('admin.appointments.*') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Appointments
                        </a>
                        @endcan

                        @can('client')
                        <a href="{{ route('appointments.create') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                                  {{ request()->routeIs('appointments.create') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Book
                        </a>
                        <a href="{{ route('appointments.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150
                                  {{ request()->routeIs('appointments.index') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            My Appointments
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- Desktop Profile Dropdown --}}
                <div class="hidden md:flex items-center">
                    @auth
                    <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border transition-all duration-200
                                       bg-white border-slate-200 hover:border-slate-300 hover:bg-slate-50">
                            <div class="w-7 h-7 rounded-lg bg-slate-600 flex items-center justify-center">
                                <span class="text-[11px] font-bold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-slate-700 max-w-[120px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200"
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
                             class="absolute right-0 mt-2 w-60 rounded-2xl overflow-hidden
                                    bg-white border border-slate-200 shadow-xl shadow-slate-200/60 z-50">

                            <div class="px-4 py-3.5 border-b border-slate-100">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-900 mt-0.5 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1.5">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile settings
                                </a>
                            </div>

                            <div class="border-t border-slate-100 py-1.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-500
                                                   hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="md:hidden p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
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
             class="md:hidden border-t border-slate-200 bg-white">
            <div class="px-4 py-3 space-y-0.5">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('dashboard') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Dashboard
                </a>
                @can('admin')
                <a href="{{ route('services.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('services.*') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Services
                </a>
                <a href="{{ route('admin.appointments.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.appointments.*') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Appointments
                </a>
                @endcan
                @can('client')
                <a href="{{ route('appointments.create') }}"
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('appointments.create') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Book Appointment
                </a>
                <a href="{{ route('appointments.index') }}"
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs('appointments.index') ? 'text-slate-600 bg-slate-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    My Appointments
                </a>
                @endcan
            </div>

            @auth
            <div class="border-t border-slate-200 px-4 py-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-600 flex items-center justify-center">
                        <span class="text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="space-y-0.5">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                        Profile settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors">
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
    <header class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            {{ $header }}
        </div>
    </header>
    @endisset

    {{-- ─── Flash Messages ─────────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- ─── Main Content ────────────────────────────────────────────────────── --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
