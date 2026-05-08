<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookEase — Simple Online Booking</title>

    {{-- Fraunces (ivarTextFont substitute) + Inter (abcdFont substitute) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Fraunces:ital,opsz,wght@0,9..144,400;1,9..144,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Dark mode initialization --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-surface-900 min-h-screen overflow-x-hidden" style="background-color: #f8f9fc;">

    <a href="#main-content" class="skip-to-content">Skip to content</a>

    {{-- ─── Sticky Nav — Deep Cosmos ───────────────────────────────────── --}}
    <header class="sticky top-0 z-50" style="background-color: #001033;">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <a href="/" class="shrink-0">
                <span class="font-sans font-semibold text-[15px] text-white" style="letter-spacing: -0.016em;">BookEase</span>
            </a>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-cta text-sm px-5 py-2">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost-dark text-sm px-4 py-2">
                        Sign In
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-cta text-sm px-5 py-2">
                        Get Started
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <main id="main-content">
        {{-- ─── Hero — full-bleed dark cosmos gradient ──────────────────── --}}
        <section class="relative overflow-hidden" style="background: linear-gradient(180deg, #001033 0%, #0050f8 55%, #5fbdf7 100%); min-height: 560px;">
            {{-- Dot-matrix texture overlay --}}
            <div class="absolute inset-0 pointer-events-none opacity-[0.06]"
                 style="background-image: radial-gradient(circle, rgba(255,255,255,1) 1px, transparent 1px); background-size: 24px 24px;"></div>

            {{-- Radial glow behind content --}}
            <div class="absolute inset-0 pointer-events-none"
                 style="background: radial-gradient(50% 70% at 50% 80%, rgba(0,128,248,0.24) 0%, rgba(95,189,247,0.16) 40%, transparent 100%);"></div>

            <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-28 text-center">

                {{-- Announcement Banner Pill --}}
                <div class="flex justify-center mb-8 animate-fade-in">
                    <div class="announce-pill">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-bold text-surface-900"
                              style="background-color: #d0f100; letter-spacing: 0.02em;">NEW</span>
                        <span class="text-surface-600" style="letter-spacing: -0.016em;">Map-based location pinning now live</span>
                        <svg class="w-3.5 h-3.5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>

                {{-- Headline — Fraunces display font --}}
                <h1 class="font-display font-normal text-white text-center mb-6 animate-fade-in-up"
                    style="font-size: 56px; line-height: 1.04; letter-spacing: -0.010em; font-feature-settings: 'ss04','ss06','ss09','ss10','ss11'; max-width: 720px; margin-left: auto; margin-right: auto;">
                    Booking made simple,<br>for you and your clients.
                </h1>

                {{-- Subheading — Inter body copy --}}
                <p class="text-[17px] text-white/70 mb-10 max-w-lg mx-auto leading-relaxed animate-fade-in-up animate-delay-100"
                   style="letter-spacing: -0.016em;">
                    BookEase lets businesses manage services and availability while clients book appointments in seconds — from any device.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 animate-fade-in-up animate-delay-200">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-cta px-7 py-3 text-[15px]">
                            Open Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-cta px-7 py-3 text-[15px]">
                            Create Free Account
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="btn-ghost-dark px-7 py-3 text-[15px]">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        {{-- ─── App Preview Card — Pure Surface elevated on Ghost Canvas ─── --}}
        <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 -mt-10 pb-20 relative z-10">
            <div class="bg-white rounded-[20px] overflow-hidden"
                 style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px, rgba(0,39,80,0.03) 0px 32px 32px -16px, rgba(0,39,80,0.03) 0px 56px 72px -16px;">
                {{-- Mock browser bar --}}
                <div class="px-5 py-3.5 bg-surface-50 border-b border-black/[0.04] flex items-center gap-3">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400/70"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400/70"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-400/70"></div>
                    </div>
                    <div class="flex-1 mx-3 bg-white border border-black/[0.06] rounded px-3 py-1 text-xs text-surface-400"
                         style="letter-spacing: -0.016em;">
                        bookease.app/appointments/create
                    </div>
                </div>

                {{-- Mock booking UI --}}
                <div class="p-6 sm:p-8">
                    <div class="grid sm:grid-cols-2 gap-6">
                        {{-- Left: Service + calendar --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-[11px] font-semibold uppercase text-surface-400 mb-2" style="letter-spacing: 0.06em;">Select service</p>
                                <div class="bg-surface-50 rounded-[16px] px-4 py-3 flex items-center justify-between"
                                     style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                                    <div>
                                        <p class="text-sm font-semibold text-surface-900" style="letter-spacing: -0.016em;">Deep Tissue Massage</p>
                                        <p class="text-xs text-surface-400 mt-0.5" style="letter-spacing: -0.016em;">60 min · $85.00</p>
                                    </div>
                                    <svg class="w-4 h-4 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold uppercase text-surface-400 mb-2" style="letter-spacing: 0.06em;">Calendar</p>
                                <div class="bg-white rounded-[16px] p-3"
                                     style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.03) 0px 4px 8px -2px;">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-sm font-semibold text-surface-900" style="letter-spacing: -0.016em;">May 2026</p>
                                        <div class="flex gap-1">
                                            <div class="w-6 h-6 rounded-md bg-surface-50 flex items-center justify-center" style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                                                <svg class="w-3 h-3 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            </div>
                                            <div class="w-6 h-6 rounded-md bg-surface-50 flex items-center justify-center" style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                                                <svg class="w-3 h-3 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-7 gap-1 text-center">
                                        @foreach(['S','M','T','W','T','F','S'] as $d)
                                        <div class="text-[10px] font-semibold text-surface-400 py-1" style="letter-spacing: 0.04em;">{{ $d }}</div>
                                        @endforeach
                                        @foreach([null,null,null,'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31',null,null] as $day)
                                        @if($day === '14')
                                        <div class="text-[11px] font-bold w-6 h-6 mx-auto rounded-full text-surface-900 flex items-center justify-center" style="background-color: #d0f100;">{{ $day }}</div>
                                        @elseif(in_array($day, ['11','12','17','18','24','25','31']))
                                        <div class="text-[11px] text-surface-300 w-6 h-6 mx-auto rounded-full flex items-center justify-center">{{ $day ?? '' }}</div>
                                        @elseif($day)
                                        <div class="text-[11px] text-surface-600 hover:bg-surface-50 hover:text-surface-900 w-6 h-6 mx-auto rounded-full flex items-center justify-center cursor-pointer transition-colors" style="letter-spacing: -0.016em;">{{ $day }}</div>
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
                                <p class="text-[11px] font-semibold uppercase text-surface-400 mb-2" style="letter-spacing: 0.06em;">Available times</p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(['9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM'] as $i => $time)
                                    @if($i === 2)
                                    <div class="rounded-[16px] text-surface-900 text-center py-2.5 text-sm font-semibold cursor-pointer" style="background-color: #d0f100; box-shadow: rgba(24,37,66,0.32) 0px 1px 3px 0px, rgba(24,37,66,0.44) 0px 8px 16px -8px; letter-spacing: -0.016em;">{{ $time }}</div>
                                    @else
                                    <div class="rounded-[16px] bg-white text-surface-600 text-center py-2.5 text-sm font-medium cursor-pointer transition-all duration-150 hover:bg-surface-50" style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px; letter-spacing: -0.016em;">{{ $time }}</div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-[16px] p-4 bg-surface-50" style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                                <p class="text-[11px] font-semibold uppercase text-surface-500 mb-2" style="letter-spacing: 0.06em;">Appointment</p>
                                <p class="text-sm font-bold text-surface-900" style="letter-spacing: -0.016em;">Wednesday, May 14 at 10:00 AM</p>
                                <p class="text-xs text-surface-400 mt-0.5" style="letter-spacing: -0.016em;">60 minute session</p>
                            </div>

                            <button class="btn-cta w-full justify-center py-3 text-[15px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirm Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ─── Features — Ghost Canvas with elevated cards ──────────────── --}}
        <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="text-center mb-14">
                <div class="inline-flex items-center gap-2 rounded-2xl bg-white px-3.5 py-1.5 mb-5"
                     style="box-shadow: rgba(0,39,80,0.06) 0px 4px 12px -2px, rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                    <span class="text-[12px] font-semibold text-surface-500 uppercase" style="letter-spacing: 0.04em;">Platform</span>
                </div>
                <h2 class="font-display font-normal text-surface-900 text-balance"
                    style="font-size: 40px; line-height: 1.05; letter-spacing: -0.010em; font-feature-settings: 'ss04','ss06','ss09','ss10','ss11';">
                    Everything you need to run bookings
                </h2>
                <p class="mt-4 text-surface-500 max-w-lg mx-auto text-[17px] leading-relaxed" style="letter-spacing: -0.016em;">
                    A simple, complete platform for businesses and the clients they serve.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                <div class="group bg-white rounded-[20px] p-5 transition-all duration-300 hover:shadow-card-hover"
                     style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px, rgba(0,39,80,0.03) 0px 32px 32px -16px;">
                    <div class="w-10 h-10 rounded-[16px] bg-surface-50 flex items-center justify-center mb-4 transition-colors duration-300 group-hover:bg-brand-50/50"
                         style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                        <svg class="w-5 h-5 text-surface-600 group-hover:text-brand-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-sans font-semibold text-surface-900 mb-1.5 text-[15px]" style="letter-spacing: -0.016em; line-height: 1.29;">{{ $feature['title'] }}</h3>
                    <p class="text-[14px] text-surface-500 leading-relaxed" style="letter-spacing: -0.016em;">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ─── CTA Section — Ghost Canvas with Cosmos Gradient card ──────── --}}
        @guest
        <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="rounded-[20px] overflow-hidden relative"
                 style="background: linear-gradient(135deg, #001033 0%, #0050f8 60%, #5fbdf7 100%); padding: 56px;">
                {{-- Dot texture --}}
                <div class="absolute inset-0 opacity-[0.05] pointer-events-none"
                     style="background-image: radial-gradient(circle, rgba(255,255,255,1) 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative text-center max-w-lg mx-auto">
                    <h2 class="font-display font-normal text-white mb-3 text-balance"
                        style="font-size: 40px; line-height: 1.05; letter-spacing: -0.010em; font-feature-settings: 'ss04','ss06','ss09','ss10','ss11';">
                        Ready to get started?
                    </h2>
                    <p class="text-white/70 mb-8 text-[16px] leading-relaxed" style="letter-spacing: -0.016em;">
                        Create your free account and start accepting bookings today. No credit card required.
                    </p>
                    <a href="{{ route('register') }}" class="btn-cta px-8 py-3.5 text-[15px]">
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

    {{-- ─── Footer ──────────────────────────────────────────────────────── --}}
    <footer class="bg-white border-t border-black/[0.05]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="font-semibold text-surface-900 text-sm" style="letter-spacing: -0.016em;">BookEase</span>
            <span class="text-sm text-surface-400" style="letter-spacing: -0.016em;">&copy; {{ date('Y') }} BookEase. All rights reserved.</span>
        </div>
    </footer>

</body>
</html>
