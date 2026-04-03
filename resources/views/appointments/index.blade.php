<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="mb-4 flex items-center justify-between">
                        <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Book New Appointment') }}
                        </a>
                    </div>

                    @if($appointments->isEmpty())
                        <p class="text-gray-500">{{ __('You have no appointments booked yet.') }}</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->service->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('F j, Y, g:i a') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($appointment->status === 'booked')
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Booked</span>
                                            @elseif($appointment->status === 'cancelled')
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Cancelled</span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Completed</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($appointment->status === 'booked')
                                                <form method="POST" action="{{ route('appointments.destroy', $appointment) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </td>                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
