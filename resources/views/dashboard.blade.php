<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-[16px] bg-surface-50 flex items-center justify-center"
                     style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                    <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[22px] font-semibold text-surface-900 dark:text-white" style="letter-spacing: -0.016em; line-height: 1.29;">Dashboard</h3>
                    <p class="text-sm text-surface-500 dark:text-ink-400" style="letter-spacing: -0.016em;">Welcome back, <span class="font-semibold text-surface-700 dark:text-surface-300">{{ Auth::user()->name }}</span></p>
                </div>
            </div>
            @php
                $roleLabel = Auth::user()->isAdmin() ? 'Admin' : (Auth::user()->isProvider() ? 'Provider' : 'Client');
                $roleVariant = Auth::user()->isAdmin() ? 'brand' : (Auth::user()->isProvider() ? 'blue' : 'emerald');
            @endphp
            <x-badge :variant="$roleVariant" class="hidden sm:inline-flex">{{ $roleLabel }}</x-badge>
        </div>
    </x-slot>

    @can('admin')
    {{-- ─── Admin Dashboard ─────────────────────────────────────────── --}}
    <div class="space-y-6 animate-fade-in">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card
                label="Services"
                :value="$stats['total_services']"
                subtitle="available"
                icon="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                href="{{ route('services.index') }}"
            />
            <x-stat-card
                label="All Bookings"
                :value="$stats['total_appointments']"
                subtitle="all time"
                color="blue"
                icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                href="{{ route('admin.appointments.index') }}"
            />
            <x-stat-card
                label="Today"
                :value="$stats['today']"
                subtitle="booked"
                color="emerald"
                icon="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
            />
            <x-stat-card
                label="This Week"
                :value="$stats['upcoming_week']"
                subtitle="upcoming"
                color="purple"
                icon="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"
            />
        </div>

        {{-- ─── Charts ──────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Bookings per day (last 14 days) --}}
            <div class="lg:col-span-2 bg-white dark:bg-ink-900 rounded-[20px] p-5"
                 style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase text-surface-400 dark:text-ink-500" style="letter-spacing: 0.06em;">Bookings</p>
                        <h4 class="text-[18px] font-semibold text-surface-900 dark:text-white mt-0.5" style="letter-spacing: -0.016em;">Last 14 days</h4>
                    </div>
                </div>
                <div style="height: 220px;"><canvas id="chart-bookings-by-day"></canvas></div>
            </div>

            {{-- Status distribution --}}
            <div class="bg-white dark:bg-ink-900 rounded-[20px] p-5"
                 style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px;">
                <div class="mb-4">
                    <p class="text-[11px] font-semibold uppercase text-surface-400 dark:text-ink-500" style="letter-spacing: 0.06em;">Status</p>
                    <h4 class="text-[18px] font-semibold text-surface-900 dark:text-white mt-0.5" style="letter-spacing: -0.016em;">Distribution</h4>
                </div>
                <div style="height: 220px;"><canvas id="chart-status"></canvas></div>
            </div>

            {{-- Top services --}}
            <div class="lg:col-span-3 bg-white dark:bg-ink-900 rounded-[20px] p-5"
                 style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px;">
                <div class="mb-4">
                    <p class="text-[11px] font-semibold uppercase text-surface-400 dark:text-ink-500" style="letter-spacing: 0.06em;">Most booked</p>
                    <h4 class="text-[18px] font-semibold text-surface-900 dark:text-white mt-0.5" style="letter-spacing: -0.016em;">Top services</h4>
                </div>
                <div style="height: 240px;"><canvas id="chart-top-services"></canvas></div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.appointments.index') }}" class="btn-dark text-sm px-5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                All Appointments
            </a>
            <a href="{{ route('services.index') }}" class="btn-ghost text-sm px-5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Services
            </a>
            <a href="{{ route('categories.index') }}" class="btn-ghost text-sm px-5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>
            <a href="{{ route('providers.index') }}" class="btn-ghost text-sm px-5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Providers
            </a>
        </div>
    </div>
    @endcan

    @can('provider')
    <div class="space-y-6 animate-fade-in">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card
                label="Assigned Services"
                :value="$stats['assigned_services']"
                icon="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                href="{{ route('services.index') }}"
            />
            <x-stat-card
                label="Upcoming"
                :value="$stats['upcoming']"
                color="blue"
                icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                href="{{ route('provider.appointments.index') }}"
            />
            <x-stat-card
                label="Today"
                :value="$stats['today']"
                color="emerald"
                icon="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
            />
            <x-stat-card
                label="Completed"
                :value="$stats['completed']"
                color="purple"
                icon="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </div>

        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('provider.appointments.index') }}" class="btn-dark text-sm px-5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                My Schedule
            </a>
            <a href="{{ route('services.index') }}" class="btn-ghost text-sm px-5 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                My Services
            </a>
        </div>
    </div>
    @endcan

    @can('client')
    {{-- ─── Client Dashboard ─────────────────────────────────────────── --}}
    <div class="space-y-6 max-w-3xl animate-fade-in">

        {{-- Next appointment highlight --}}
        @if($stats['next'])
        <div class="bg-white dark:bg-ink-900 rounded-[20px] p-6"
             style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px, rgba(0,39,80,0.03) 0px 32px 32px -16px;">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <p class="text-[11px] font-semibold uppercase text-surface-400 dark:text-ink-500" style="letter-spacing: 0.06em;">Next Appointment</p>
            </div>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[18px] font-semibold text-surface-900 dark:text-white" style="letter-spacing: -0.016em;">{{ $stats['next']->service->name }}</p>
                    <p class="mt-1.5 text-sm text-surface-500 dark:text-ink-400 flex items-center gap-1.5" style="letter-spacing: -0.016em;">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        {{ \Carbon\Carbon::parse($stats['next']->scheduled_at)->format('l, F j · g:i a') }}
                    </p>
                    @if($stats['next']->notes)
                    <p class="mt-2 text-xs text-surface-400 dark:text-ink-500 italic" style="letter-spacing: -0.016em;">{{ $stats['next']->notes }}</p>
                    @endif
                </div>
                <x-badge variant="emerald" :hasDot="true">Upcoming</x-badge>
            </div>
            <div class="mt-5 pt-4 border-t border-black/[0.04] dark:border-white/[0.04] flex flex-wrap gap-2">
                <a href="{{ route('appointments.reschedule', $stats['next']) }}" class="btn-ghost text-sm px-4 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Reschedule
                </a>
                <a href="{{ route('appointments.index') }}" class="btn-ghost text-sm px-4 py-2">
                    View All
                </a>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-ink-900 rounded-[20px] p-6"
             style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px, rgba(0,39,80,0.04) 0px 6px 12px -3px;">
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-[16px] bg-surface-50 dark:bg-ink-800 flex items-center justify-center"
                     style="box-shadow: rgba(0,39,80,0.04) 0px 0px 0px 1px;">
                    <svg class="w-7 h-7 text-surface-400 dark:text-ink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
                <p class="text-sm text-surface-500 dark:text-ink-400 mb-5" style="letter-spacing: -0.016em;">You have no upcoming appointments.</p>
                <a href="{{ route('services.browse') }}" class="btn-dark text-sm px-5 py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Browse Services
                </a>
            </div>
        </div>
        @endif

        {{-- Quick stats --}}
        <div class="grid grid-cols-3 gap-4">
            <x-stat-card
                label="Upcoming"
                :value="$stats['upcoming']"
                color="blue"
                icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
            />
            <x-stat-card
                label="Completed"
                :value="$stats['completed']"
                color="emerald"
                icon="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
            <x-stat-card
                label="Total"
                :value="$stats['total']"
                color="purple"
                icon="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
            />
        </div>
    </div>
    @endcan

    @can('admin')
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const charts = @json($stats['charts']);
            const navy = '#1b2540';
            const cosmos = '#001033';
            const chartreuse = '#d0f100';
            const slate = '#6b7184';
            const fog = '#b1b5c0';

            Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = slate;

            // Bookings per day — line
            new Chart(document.getElementById('chart-bookings-by-day'), {
                type: 'line',
                data: {
                    labels: charts.bookingsByDay.labels,
                    datasets: [{
                        label: 'Bookings',
                        data: charts.bookingsByDay.data,
                        borderColor: cosmos,
                        backgroundColor: 'rgba(0, 80, 248, 0.08)',
                        borderWidth: 2,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: cosmos,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: slate } },
                        y: { beginAtZero: true, ticks: { precision: 0, color: slate }, grid: { color: 'rgba(0,39,80,0.06)' } },
                    },
                },
            });

            // Status distribution — donut
            new Chart(document.getElementById('chart-status'), {
                type: 'doughnut',
                data: {
                    labels: ['Booked', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            charts.statusDistribution.booked,
                            charts.statusDistribution.completed,
                            charts.statusDistribution.cancelled,
                        ],
                        backgroundColor: [cosmos, chartreuse, fog],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, pointStyle: 'circle' } },
                    },
                },
            });

            // Top services — horizontal bar
            new Chart(document.getElementById('chart-top-services'), {
                type: 'bar',
                data: {
                    labels: charts.topServices.labels,
                    datasets: [{
                        label: 'Bookings',
                        data: charts.topServices.data,
                        backgroundColor: cosmos,
                        borderRadius: 6,
                        barThickness: 18,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { precision: 0, color: slate }, grid: { color: 'rgba(0,39,80,0.06)' } },
                        y: { grid: { display: false }, ticks: { color: navy } },
                    },
                },
            });
        });
    </script>
    @endpush
    @endcan
</x-app-layout>
