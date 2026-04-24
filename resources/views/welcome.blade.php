<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookEase — Premium Scheduling Infrastructure</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-300 min-h-screen bg-zinc-950 selection:bg-brand-500 selection:text-white overflow-x-hidden">

    {{-- Background Effects --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-grid-pattern-dark [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-brand-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-brand-900/40 rounded-full mix-blend-screen filter blur-[100px] animate-blob" style="animation-delay: 2s"></div>
    </div>

    {{-- Nav --}}
    <header class="fixed top-0 w-full z-50 bg-zinc-950/50 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center rounded-lg shadow-glow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 .477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 .477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight text-white">BookEase</span>
            </div>
            
            <div class="flex items-center gap-4 text-sm font-medium">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition-colors">Sign In</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-zinc-900 hover:bg-slate-200 rounded-lg transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                        Get Early Access
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <main class="relative z-10 pt-24 pb-16">
        {{-- Hero --}}
        <section class="max-w-5xl mx-auto px-6 py-20 sm:py-28 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-900/30 border border-brand-500/30 text-brand-400 text-xs font-medium tracking-wide uppercase mb-8 ring-1 ring-inset ring-brand-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
                BookEase 2.0 is Here
            </div>
            
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400 mb-8 leading-tight">
                High-performance scheduling<br>architecture.
            </h1>
            
            <p class="text-lg sm:text-xl text-slate-400 mb-10 leading-relaxed max-w-2xl mx-auto">
                A blazingly fast, developer-first platform to manage availability logic, capacity limits, and routing at scale. 
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3 bg-white text-zinc-900 hover:bg-slate-200 font-semibold rounded-xl transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)] flex items-center justify-center gap-2">
                        Open Workspace
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3 bg-white text-zinc-900 hover:bg-slate-200 font-semibold rounded-xl transition-all shadow-[0_0_20px_rgba(255,255,255,0.15)] flex items-center justify-center gap-2">
                        Start Building Free
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3 bg-zinc-900/50 border border-white/10 hover:bg-zinc-800/80 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                        Read Docs
                    </a>
                @endauth
            </div>
        </section>

        {{-- Local Business Features --}}
        <section class="max-w-6xl mx-auto px-6 py-24">
            
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                {{-- Left: Copy & Menu --}}
                <div>
                    <div class="mb-8">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-white mb-4">Streamline your local business.</h2>
                        <p class="text-slate-400 text-lg leading-relaxed">BookEase is built for salons, clinics, and studios. Manage your staff schedule, accept payments, and let clients book 24/7.</p>
                    </div>

                    <div class="space-y-6">
                        <!-- Active Feature -->
                        <div class="group relative pl-6 border-l-2 border-brand-500">
                            <h4 class="text-lg font-semibold text-white mb-2">Service Portfolio Mapping</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">Define precise locations, durations, and dynamic pricing for all your physical services. Easily route clients to the right branch.</p>
                        </div>
                        
                        <!-- Inactive Feature 1 -->
                        <div class="group relative pl-6 border-l-2 border-white/10 hover:border-white/30 transition-colors">
                            <h4 class="text-lg font-semibold text-slate-300 group-hover:text-white transition-colors mb-2">Automated Notifications</h4>
                            <p class="text-sm text-slate-500 group-hover:text-slate-400 leading-relaxed transition-colors">Drastically reduce no-shows. Send automatic SMS and email reminders to your clients before their appointment.</p>
                        </div>

                        <!-- Inactive Feature 2 -->
                        <div class="group relative pl-6 border-l-2 border-white/10 hover:border-white/30 transition-colors">
                            <h4 class="text-lg font-semibold text-slate-300 group-hover:text-white transition-colors mb-2">Location & Map Routing</h4>
                            <p class="text-sm text-slate-500 group-hover:text-slate-400 leading-relaxed transition-colors">Built-in geocoding ensures your clients always know exact coordinates and addresses of their upcoming session.</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Clean Booking UI Mockup (Dark Mode) --}}
                <div class="relative w-full max-w-sm mx-auto lg:ml-auto select-none">
                    <!-- Subtle background aura -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-brand-500/10 blur-[100px] rounded-full pointer-events-none"></div>

                    <!-- Main Widget Container -->
                    <div class="relative bg-zinc-950/80 backdrop-blur-xl rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.7)] overflow-hidden border border-white/10 font-sans text-white">
                        <!-- Profile Header -->
                        <div class="p-6 pb-4 border-b border-white/5 flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-brand-400 to-emerald-500 mb-3 shadow-[0_0_15px_rgba(var(--color-brand-500),0.3)] flex items-center justify-center text-white text-xl font-bold">
                                GS
                            </div>
                            <h3 class="font-semibold text-lg text-slate-100">Glow Spa & Studio</h3>
                            <p class="text-slate-400 text-sm">Deep Tissue Massage</p>
                            <div class="flex flex-wrap justify-center items-center gap-3 mt-4 text-xs font-medium text-brand-300">
                                <span class="bg-brand-500/10 border border-brand-500/20 px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    60 min
                                </span>
                                <span class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    $85.00
                                </span>
                                <span class="bg-white/5 border border-white/10 text-slate-300 px-3 py-1.5 rounded-full flex items-center gap-1.5 w-full justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    124 Downtown Ave
                                </span>
                            </div>
                        </div>
                        
                        <!-- Layout: Mini Calendar + Times -->
                        <div class="p-6 bg-black/20">
                            <h4 class="font-semibold text-sm mb-4 text-slate-200">Select Date & Time</h4>
                            
                            <!-- Static Calendar Days Row -->
                            <div class="grid grid-cols-5 gap-2 mb-6">
                                @foreach([['Mon', '12', false], ['Tue', '13', false], ['Wed', '14', true], ['Thu', '15', false], ['Fri', '16', false]] as [$day, $num, $isActive])
                                <div class="flex flex-col items-center p-2 rounded-xl {{ $isActive ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'bg-white/5 border border-white/5 text-slate-300' }}">
                                    <span class="text-[10px] uppercase font-bold {{ $isActive ? 'text-brand-100' : 'text-slate-500' }}">{{ $day }}</span>
                                    <span class="text-sm font-semibold mt-0.5">{{ $num }}</span>
                                </div>
                                @endforeach
                            </div>

                            <!-- Time Slots Row -->
                            <div class="space-y-2.5">
                                <div class="w-full py-2.5 px-4 bg-white/5 border border-white/5 rounded-xl text-center text-sm font-medium hover:border-brand-500/50 hover:bg-brand-500/10 text-slate-300 cursor-pointer transition-all">
                                    09:00 AM
                                </div>
                                <div class="w-full py-2.5 px-4 bg-white text-zinc-900 border border-white rounded-xl flex items-center justify-between text-sm font-semibold cursor-pointer shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                                    <span>10:30 AM</span>
                                    <span class="text-[11px] bg-zinc-900 text-white px-2 py-0.5 rounded-md font-medium">Confirm</span>
                                </div>
                                <div class="w-full py-2.5 px-4 bg-white/5 border border-white/5 rounded-xl text-center text-sm font-medium hover:border-brand-500/50 hover:bg-brand-500/10 text-slate-300 cursor-pointer transition-all">
                                    01:15 PM
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Platform Highlights -->
            <div class="mt-20 pt-10 border-t border-white/5 grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Item 1 -->
                <div>
                    <div class="flex items-center gap-2 mb-2 text-white">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <h5 class="font-semibold text-sm">Bank-grade Security</h5>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Your data is stored securely with end-to-end encryption so your clients' privacy is always guaranteed.</p>
                </div>
                <!-- Item 2 -->
                <div>
                    <div class="flex items-center gap-2 mb-2 text-white">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h5 class="font-semibold text-sm">Any Timezone</h5>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Bookings automatically adapt to local timezones, making international clients a breeze.</p>
                </div>
                <!-- Item 3 -->
                <div>
                    <div class="flex items-center gap-2 mb-2 text-white">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V9a2 2 0 00-2-2m2 4v4a2 2 0 104 0v-1m-4-3H9m2 0h4m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h5 class="font-semibold text-sm">Payment Integration</h5>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Require deposits or full payments seamlessly during the booking flow via Stripe or PayPal.</p>
                </div>
                <!-- Item 4 -->
                <div>
                    <div class="flex items-center gap-2 mb-2 text-white">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <h5 class="font-semibold text-sm">Mobile Optimized</h5>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Every part of the scheduling experience operates perfectly on mobile browsers.</p>
                </div>
            </div>

        </section>

        {{-- Stats --}}
        <section class="max-w-5xl mx-auto px-6 py-12">
            <div class="border-t border-white/10 pt-12 flex flex-col md:flex-row justify-between gap-8 text-center md:text-left">
                @foreach ([['2B+', 'Bookings Handled'], ['99.99%', 'Uptime SLA'], ['< 40ms', 'Avg. Latency'], ['80+', 'Global Nodes']] as [$val, $label])
                <div>
                    <h4 class="text-4xl font-extrabold text-white tracking-tight">{{ $val }}</h4>
                    <p class="text-sm font-medium text-slate-500 tracking-wide uppercase mt-2">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-white/10 bg-black/50 backdrop-blur-lg">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-brand-500 rounded-sm"></div>
                <span class="text-white font-semibold">BookEase Inc.</span>
            </div>
            <span>&copy; {{ date('Y') }} All rights reserved.</span>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Terms</a>
                <a href="#" class="hover:text-white transition-colors">System Status</a>
            </div>
        </div>
    </footer>

</body>
</html>