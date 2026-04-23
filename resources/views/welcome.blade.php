<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookEase — Smart Appointment Booking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-900 min-h-screen">

    {{-- ─── Nav ─────────────────────────────────────────────────────────────── --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center shadow-md shadow-indigo-600/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-[17px] font-bold tracking-tight text-slate-900">Book<span class="text-indigo-600">Ease</span></span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                        Sign in
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        Get started free
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    {{-- ─── Hero ────────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-white">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-50 via-white to-white pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 lg:px-8 pt-28 pb-24 text-center relative">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-600 text-xs font-semibold tracking-wide uppercase mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                Now in Beta · Free to Try
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 leading-[1.08] mb-6">
                Book smarter.<br>
                <span class="text-indigo-600">Not harder.</span>
            </h1>

            <p class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed mb-10">
                BookEase is your all-in-one scheduling platform — manage services, appointments, and clients all from a single, beautiful dashboard.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-7 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-600/20 text-sm">
                        Open Dashboard →
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="px-7 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-600/20 text-sm">
                        Start for free →
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-7 py-3.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold rounded-xl transition-all shadow-sm text-sm">
                        Sign in
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- ─── Stats ───────────────────────────────────────────────────────────── --}}
    <section class="border-y border-slate-200 bg-slate-50">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['10k+', 'Appointments booked'],
                ['500+', 'Active services'],
                ['99.9%', 'Uptime SLA'],
                ['4.9★', 'Average rating'],
            ] as [$number, $label])
            <div>
                <p class="text-3xl font-bold text-slate-900">{{ $number }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ─── Features ────────────────────────────────────────────────────────── --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Everything you need, nothing you don't</h2>
            <p class="text-slate-500 max-w-xl mx-auto">A focused set of tools designed to make booking effortless for both businesses and clients.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ([
                [
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    'title' => 'Service Management',
                    'desc'  => 'Create and configure your services with custom pricing, duration, and location. Full admin control in one place.',
                ],
                [
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'title' => 'Smart Scheduling',
                    'desc'  => 'Clients pick their slot, you get notified. Track status — booked, completed, or cancelled — from a clean timeline view.',
                ],
                [
                    'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                    'title' => 'Location Maps',
                    'desc'  => 'Pin your exact service location on a map so clients always know where to go. Built-in with OpenStreetMap — no API key needed.',
                ],
            ] as $feature)
            <div class="group bg-white border border-slate-200 rounded-2xl p-7 hover:border-indigo-200 hover:shadow-md transition-all duration-300">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-900 mb-2">{{ $feature['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ─── CTA ─────────────────────────────────────────────────────────────── --}}
    <section class="max-w-4xl mx-auto px-6 lg:px-8 pb-24">
        <div class="rounded-3xl bg-indigo-600 p-12 text-center shadow-xl shadow-indigo-600/20">
            <h2 class="text-3xl font-bold text-white mb-3">Ready to streamline your bookings?</h2>
            <p class="text-indigo-200 mb-8 max-w-md mx-auto">Join hundreds of businesses using BookEase to save time and delight their customers.</p>
            @guest
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 bg-white hover:bg-slate-50 text-indigo-600 font-semibold rounded-xl transition-all shadow-sm text-sm">
                Create your free account →
            </a>
            @endguest
            @auth
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 bg-white hover:bg-slate-50 text-indigo-600 font-semibold rounded-xl transition-all shadow-sm text-sm">
                Go to Dashboard →
            </a>
            @endauth
        </div>
    </section>

    {{-- ─── Footer ──────────────────────────────────────────────────────────── --}}
    <footer class="border-t border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-sm font-semibold text-slate-500">Book<span class="text-indigo-600">Ease</span></span>
            <p class="text-xs text-slate-400">© {{ date('Y') }} BookEase. Built with Laravel &amp; Tailwind CSS.</p>
        </div>
    </footer>

</body>
</html>
