<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookEase — Simple Online Booking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen overflow-x-hidden">

    <a href="#main-content" class="skip-to-content">Skip to content</a>

    {{-- ─── Nav ─────────────────────────────────────────────────────── --}}
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/80 dark:border-slate-700/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-brand-600 dark:bg-brand-500 shadow-md shadow-brand-500/25 group-hover:shadow-glow group-hover:-translate-y-px transition-all duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Book<span class="text-brand-600 dark:text-brand-400">Ease</span></span>
            </a>

            <div class="flex items-center gap-2">
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

                <div class="flex items-center gap-2 text-sm font-medium">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/30 hover:-translate-y-px active:translate-y-px">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 rounded-xl transition-colors duration-150">
                            Sign In
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/30 hover:-translate-y-px active:translate-y-px">
                            Get Started
                        </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main id="main-content">
        {{-- ─── Hero ───────────────────────────────────────────────────── --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 text-center relative">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full bg-brand-400/10 dark:bg-brand-500/10 blur-3xl pointer-events-none"></div>
            <div class="relative">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 dark:bg-brand-900/30 border border-brand-200 dark:border-brand-800 text-brand-700 dark:text-brand-300 text-xs font-semibold tracking-wide uppercase mb-8 animate-fade-in">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Online Booking — Now Open
                </div>

                <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-6 leading-tight animate-fade-in-up">
                    Booking made simple,<br class="hidden sm:block"> for you and your clients.
                </h1>

                <p class="text-lg text-slate-500 dark:text-slate-400 mb-10 max-w-xl mx-auto leading-relaxed animate-fade-in-up animate-delay-100">
                    BookEase lets businesses manage services and availability while clients book appointments in seconds — from any device.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 animate-fade-in-up animate-delay-200">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/30 hover:-translate-y-px active:translate-y-px">
                            Open Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/30 hover:-translate-y-px active:translate-y-px">
                            Create Free Account
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 hover:border-brand-200 dark:hover:border-brand-500 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-all duration-150 hover:shadow-sm">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        {{-- ─── App Preview Card ───────────────────────────────────────── --}}
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-soft-lg overflow-hidden">
                {{-- Mock browser bar --}}
                <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                    </div>
                    <div class="flex-1 mx-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-md px-3 py-1 text-xs text-slate-400 dark:text-slate-500">
                        bookease.app/appointments/create
                    </div>
                </div>

                {{-- Mock booking UI --}}
                <div class="p-6 sm:p-8">
                    <div class="grid sm:grid-cols-2 gap-6">
                        {{-- Left: Service + calendar strip --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Select service</p>
                                <div class="bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">Deep Tissue Massage</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">60 min · $85.00</p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Calendar</p>
                                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">April 2026</p>
                                        <div class="flex gap-1">
                                            <div class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            </div>
                                            <div class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-7 gap-1 text-center">
                                        @foreach(['S','M','T','W','T','F','S'] as $d)
                                        <div class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 py-1">{{ $d }}</div>
                                        @endforeach
                                        @foreach([null,null,'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30',null] as $day)
                                        @if($day === '25')
                                        <div class="text-[11px] font-bold w-6 h-6 mx-auto rounded-full bg-brand-600 dark:bg-brand-500 text-white flex items-center justify-center shadow-sm shadow-brand-500/25">{{ $day }}</div>
                                        @elseif(in_array($day, ['13','14','20','21','27','28']))
                                        <div class="text-[11px] text-slate-300 dark:text-slate-600 w-6 h-6 mx-auto rounded-full flex items-center justify-center">{{ $day ?? '' }}</div>
                                        @elseif($day && $day < '25')
                                        <div class="text-[11px] text-slate-300 dark:text-slate-600 w-6 h-6 mx-auto rounded-full flex items-center justify-center">{{ $day }}</div>
                                        @elseif($day)
                                        <div class="text-[11px] text-slate-600 dark:text-slate-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 hover:text-brand-600 w-6 h-6 mx-auto rounded-full flex items-center justify-center cursor-pointer transition-colors">{{ $day }}</div>
                                        @else
                                        <div></div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Time slots + confirm --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Available times</p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(['9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM'] as $i => $time)
                                    @if($i === 2)
                                    <div class="rounded-xl border border-brand-600 dark:border-brand-500 bg-brand-600 dark:bg-brand-500 text-white text-center py-2.5 text-sm font-semibold shadow-md shadow-brand-500/25">{{ $time }}</div>
                                    @else
                                    <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-center py-2.5 text-sm font-medium hover:border-brand-300 dark:hover:border-brand-500 hover:bg-brand-50/50 dark:hover:bg-brand-900/20 cursor-pointer transition-colors">{{ $time }}</div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-brand-50 dark:bg-brand-900/20 rounded-xl border border-brand-200 dark:border-brand-800 p-4">
                                <p class="text-xs font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400 mb-2">Appointment</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Friday, April 25 at 10:00 AM</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">60 minute session</p>
                            </div>

                            <div class="w-full py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white text-sm font-semibold rounded-xl text-center transition-all duration-200 flex items-center justify-center gap-2 shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/30 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirm Booking
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ─── Features ───────────────────────────────────────────────── --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="text-center mb-14">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-800 text-brand-600 dark:text-brand-400 text-xs font-semibold tracking-wide uppercase mb-4 animate-fade-in">Powerful Features</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight text-balance">Everything you need to run bookings</h2>
                <p class="mt-4 text-slate-500 dark:text-slate-400 max-w-xl mx-auto text-lg">A simple, complete platform for businesses and the clients they serve.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
                @php
                $features = [
                    [
                        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'title' => 'Visual calendar booking',
                        'desc' => 'Clients pick a date and time from a live calendar. Weekends and taken slots are automatically locked.',
                    ],
                    [
                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                        'title' => 'Service management',
                        'desc' => 'Create services with name, price, duration, and location. Admins control the full service catalog.',
                    ],
                    [
                        'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                        'title' => 'Map-based locations',
                        'desc' => 'Pin your exact location on a map. Clients see it when booking so they always know where to go.',
                    ],
                    [
                        'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                        'title' => 'Reschedule anytime',
                        'desc' => 'Clients can reschedule or cancel bookings themselves, reducing back-and-forth for your team.',
                    ],
                    [
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'title' => 'Email confirmations',
                        'desc' => 'Automatic confirmation emails go out the moment a booking is made, keeping everyone in the loop.',
                    ],
                    [
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                        'title' => 'Admin dashboard',
                        'desc' => "See today's appointments, mark sessions as complete, and monitor your business at a glance.",
                    ],
                ];
                @endphp

                @foreach($features as $feature)
                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:border-brand-200 dark:hover:border-brand-700 hover:shadow-soft dark:hover:shadow-soft-lg transition-all duration-300">
                    <div class="w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-900/20 flex items-center justify-center mb-4 group-hover:bg-brand-100 dark:group-hover:bg-brand-900/40 group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ─── CTA ────────────────────────────────────────────────────── --}}
        @guest
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="bg-gradient-to-br from-brand-600 to-brand-700 dark:from-brand-700 dark:to-blue-900 rounded-3xl p-10 sm:p-14 text-center shadow-soft-lg relative overflow-hidden">
                <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.08) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(255,255,255,0.05) 0%, transparent 50%);"></div>
                <div class="relative">
                    <h2 class="text-3xl font-extrabold text-white tracking-tight mb-3">Ready to get started?</h2>
                    <p class="text-brand-100 dark:text-blue-200 mb-8 max-w-md mx-auto">Create your free account and start accepting bookings today. No credit card required.</p>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-brand-700 dark:text-brand-600 hover:bg-brand-50 hover:text-brand-800 font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-px active:translate-y-px">
                        Create Free Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
        @endguest
    </main>

    {{-- ─── Footer ─────────────────────────────────────────────────── --}}
    <footer class="border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-400 dark:text-slate-500">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-brand-600 dark:bg-brand-500 flex items-center justify-center shadow-sm shadow-brand-500/25">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-semibold text-slate-700 dark:text-slate-300">BookEase</span>
            </div>
            <span class="text-slate-400 dark:text-slate-500">&copy; {{ date('Y') }} BookEase. All rights reserved.</span>
        </div>
    </footer>

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
