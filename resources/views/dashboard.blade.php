<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-0.5">Welcome back, {{ Auth::user()->name }}</p>
    </x-slot>

    @can('admin')
    {{-- ─── Admin Stats ──────────────────────────────────────────── --}}
    <div class="space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Services</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['total_services'] }}</p>
                <p class="mt-1 text-sm text-slate-500">available</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">All Bookings</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['total_appointments'] }}</p>
                <p class="mt-1 text-sm text-slate-500">all time</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Today</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['today'] }}</p>
                <p class="mt-1 text-sm text-slate-500">booked</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">This Week</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['upcoming_week'] }}</p>
                <p class="mt-1 text-sm text-slate-500">upcoming</p>
            </x-card>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.appointments.index') }}">
                <x-primary-button>View All Appointments</x-primary-button>
            </a>
            <a href="{{ route('services.index') }}">
                <x-secondary-button>Manage Services</x-secondary-button>
            </a>
        </div>
    </div>
    @endcan

    @can('client')
    {{-- ─── Client Stats ──────────────────────────────────────────── --}}
    <div class="space-y-6 max-w-3xl">

        {{-- Next appointment highlight --}}
        @if($stats['next'])
        <x-card>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">Next Appointment</p>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-lg font-bold text-slate-900">{{ $stats['next']->service->name }}</p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($stats['next']->scheduled_at)->format('l, F j · g:i a') }}
                    </p>
                    @if($stats['next']->notes)
                    <p class="mt-2 text-xs text-slate-400 italic">{{ $stats['next']->notes }}</p>
                    @endif
                </div>
                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border bg-blue-50 text-blue-700 border-blue-200">
                    Upcoming
                </span>
            </div>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('appointments.reschedule', $stats['next']) }}">
                    <x-secondary-button>Reschedule</x-secondary-button>
                </a>
                <a href="{{ route('appointments.index') }}">
                    <x-secondary-button>View All</x-secondary-button>
                </a>
            </div>
        </x-card>
        @else
        <x-card>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">Next Appointment</p>
            <p class="text-slate-500 text-sm mb-4">You have no upcoming appointments.</p>
            <a href="{{ route('appointments.create') }}">
                <x-primary-button>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Book Now
                </x-primary-button>
            </a>
        </x-card>
        @endif

        {{-- Quick stats --}}
        <div class="grid grid-cols-3 gap-4">
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Upcoming</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['upcoming'] }}</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Completed</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['completed'] }}</p>
            </x-card>
            <x-card>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Total</p>
                <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['total'] }}</p>
            </x-card>
        </div>
    </div>
    @endcan
</x-app-layout>
