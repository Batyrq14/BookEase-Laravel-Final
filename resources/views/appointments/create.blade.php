<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <!-- Service -->
                        <div class="mt-4">
                            <x-input-label for="service_id" :value="__('Service')" />
                            <select id="service_id" name="service_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="">{{ __('Select a service') }}</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} ({{ $service->duration_minutes }} mins) - ${{ $service->price }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <!-- Appointment Time -->
                        <div class="mt-4">
                            <x-input-label for="appointment_time" :value="__('Appointment Date & Time')" />
                            <x-text-input id="appointment_time" class="block mt-1 w-full" type="datetime-local" name="appointment_time" :value="old('appointment_time')" required />
                            <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('appointments.index') }}">
                                {{ __('Back to My Appointments') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Book Now') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
