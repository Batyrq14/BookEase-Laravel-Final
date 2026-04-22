<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-0.5">Welcome back, {{ Auth::user()->name }}</p>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-2xl">

        <x-card title="Book Appointment">
            <p class="text-slate-500 text-sm mb-4">Schedule a new appointment with one of our services.</p>
            <a href="{{ route('appointments.create') }}">
                <x-primary-button class="w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Appointment
                </x-primary-button>
            </a>
        </x-card>

        <x-card title="My Appointments">
            <p class="text-slate-500 text-sm mb-4">View and manage your upcoming and past bookings.</p>
            <a href="{{ route('appointments.index') }}">
                <x-primary-button class="w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Appointments
                </x-primary-button>
            </a>
        </x-card>

    </div>
</x-app-layout>
