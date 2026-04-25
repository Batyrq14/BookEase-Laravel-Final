<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookEase — Simple Online Booking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen overflow-x-hidden">

    {{-- ─── Nav ─────────────────────────────────────────────────────── --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-slate-600 flex items-center justify-center shadow-md shadow-slate-600/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-[17px] font-bold tracking-tight text-slate-900">Book<span class="text-slate-600">Ease</span></span>
            </a>

            <div class="flex items-center gap-3 text-sm font-medium">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-xl transition-colors">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-3.5 py-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                        Sign In
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-xl transition-colors shadow-sm shadow-slate-600/20">
                        Get Started
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <main>
        {{-- ─── Hero ───────────────────────────────────────────────────── --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 text-center">

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-semibold tracking-wide uppercase mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Online Booking — Now Open
            </div>

            <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
                Booking made simple,<br class="hidden sm:block"> for you and your clients.
            </h1>

            <p class="text-lg text-slate-500 mb-10 max-w-xl mx-auto leading-relaxed">
                BookEase lets businesses manage services and availability while clients book appointments in seconds — from any device.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-xl transition-colors shadow-sm shadow-slate-600/20">
                        Open Dashboard
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-xl transition-colors shadow-sm shadow-slate-600/20">
                        Create Free Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold rounded-xl transition-colors">
                        Sign In
                    </a>
                @endauth
            </div>
        </section>

        {{-- ─── App Preview Card ───────────────────────────────────────── --}}
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 overflow-hidden">
                {{-- Mock browser bar --}}
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                    </div>
                    <div class="flex-1 mx-3 bg-white border border-slate-200 rounded-md px-3 py-1 text-xs text-slate-400">
                        bookease.app/appointments/create
                    </div>
                </div>

                {{-- Mock booking UI --}}
                <div class="p-6 sm:p-8">
                    <div class="grid sm:grid-cols-2 gap-6">
                        {{-- Left: Service + calendar strip --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Select service</p>
                                <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Deep Tissue Massage</p>
                                        <p class="text-xs text-slate-400 mt-0.5">60 min · $85.00</p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Calendar</p>
                                <div class="bg-white border border-slate-200 rounded-xl p-3">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-sm font-semibold text-slate-700">April 2026</p>
                                        <div class="flex gap-1">
                                            <div class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            </div>
                                            <div class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-7 gap-1 text-center">
                                        @foreach(['S','M','T','W','T','F','S'] as $d)
                                        <div class="text-[10px] font-semibold text-slate-400 py-1">{{ $d }}</div>
                                        @endforeach
                                        @foreach([null,null,'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30',null] as $day)
                                        @if($day === '25')
                                        <div class="text-[11px] font-bold w-6 h-6 mx-auto rounded-full bg-slate-700 text-white flex items-center justify-center">{{ $day }}</div>
                                        @elseif(in_array($day, ['13','14','20','21','27','28']))
                                        <div class="text-[11px] text-slate-300 w-6 h-6 mx-auto rounded-full flex items-center justify-center">{{ $day ?? '' }}</div>
                                        @elseif($day && $day < '25')
                                        <div class="text-[11px] text-slate-300 w-6 h-6 mx-auto rounded-full flex items-center justify-center">{{ $day }}</div>
                                        @elseif($day)
                                        <div class="text-[11px] text-slate-600 hover:bg-slate-100 w-6 h-6 mx-auto rounded-full flex items-center justify-center cursor-pointer">{{ $day }}</div>
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
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Available times</p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(['9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM'] as $i => $time)
                                    @if($i === 2)
                                    <div class="rounded-xl border border-slate-900 bg-slate-900 text-white text-center py-2.5 text-sm font-semibold">{{ $time }}</div>
                                    @else
                                    <div class="rounded-xl border border-slate-200 bg-white text-slate-600 text-center py-2.5 text-sm font-medium">{{ $time }}</div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4">
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Appointment</p>
                                <p class="text-sm font-bold text-slate-900">Friday, April 25 at 10:00 AM</p>
                                <p class="text-xs text-slate-500 mt-0.5">60 minute session</p>
                            </div>

                            <div class="w-full py-3 bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl text-center transition-colors flex items-center justify-center gap-2">
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
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Everything you need to run bookings</h2>
                <p class="mt-3 text-slate-500 max-w-xl mx-auto">A simple, complete platform for businesses and the clients they serve.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                {{-- Feature 1 --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Visual calendar booking</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Clients pick a date and time from a live calendar. Weekends and taken slots are automatically locked.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Service management</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Create services with name, price, duration, and location. Admins control the full service catalog.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Map-based locations</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Pin your exact location on a map. Clients see it when booking so they always know where to go.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Reschedule anytime</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Clients can reschedule or cancel bookings themselves, reducing back-and-forth for your team.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Email confirmations</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Automatic confirmation emails go out the moment a booking is made, keeping everyone in the loop.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Admin dashboard</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">See today's appointments, mark sessions as complete, and monitor your business at a glance.</p>
                </div>
            </div>
        </section>

        {{-- ─── CTA ────────────────────────────────────────────────────── --}}
        @guest
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="bg-slate-900 rounded-2xl p-10 sm:p-14 text-center">
                <h2 class="text-3xl font-extrabold text-white tracking-tight mb-3">Ready to get started?</h2>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">Create your free account and start accepting bookings today. No credit card required.</p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 bg-white text-slate-900 hover:bg-slate-100 font-semibold rounded-xl transition-colors">
                    Create Free Account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </section>
        @endguest
    </main>

    {{-- ─── Footer ─────────────────────────────────────────────────── --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-400">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-slate-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-semibold text-slate-700">BookEase</span>
            </div>
            <span>&copy; {{ date('Y') }} BookEase. All rights reserved.</span>
        </div>
    </footer>

</body>
</html>
