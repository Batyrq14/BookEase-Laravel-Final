<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Book Appointment -->
            <a href="{{ route('appointments.create') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-bold mb-2">Book Appointment</h3>
                <p class="text-gray-600">Schedule a new appointment</p>
            </a>

            <!-- My Appointments -->
            <a href="{{ route('appointments.index') }}" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-bold mb-2">My Appointments</h3>
                <p class="text-gray-600">View and manage your bookings</p>
            </a>

        </div>
    </div>
</x-app-layout>