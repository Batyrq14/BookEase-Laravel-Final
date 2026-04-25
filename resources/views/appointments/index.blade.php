<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">My Appointments</h1>
                <p class="text-sm text-slate-500 mt-0.5">View and manage your bookings</p>
            </div>
            <a href="{{ route('appointments.create') }}">
                <x-primary-button type="button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Book Appointment
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-card :padding="false">
        @if(isset($appointments) && $appointments->count())
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Notes</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($appointments as $appointment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $appointment->service->name }}</td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M j, Y · g:i a') }}
                    </td>
                    <td class="px-6 py-4 max-w-[160px]">
                        @if($appointment->notes)
                            <p class="text-slate-500 text-xs line-clamp-2">{{ $appointment->notes }}</p>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $badge = match($appointment->status) {
                                'booked'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default     => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($appointment->status === 'booked')
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('appointments.reschedule', $appointment) }}"
                               class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                Reschedule
                            </a>
                            <form method="POST" action="{{ route('appointments.destroy', $appointment) }}"
                                  onsubmit="return confirm('Cancel this appointment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    Cancel
                                </button>
                            </form>
                        </div>
                        @elseif($appointment->status === 'cancelled')
                        <a href="{{ route('appointments.create', ['service_id' => $appointment->service_id]) }}"
                           class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Book Again
                        </a>
                        @else
                        <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-slate-700 font-semibold mb-1">No appointments yet</p>
            <p class="text-slate-400 text-sm mb-5">Book your first appointment to get started</p>
            <a href="{{ route('appointments.create') }}">
                <x-primary-button type="button">Book now</x-primary-button>
            </a>
        </div>
        @endif
    </x-card>
</x-app-layout>
