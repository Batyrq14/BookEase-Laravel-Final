<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">My Upcoming Appointments</h1>
                <p class="text-sm text-slate-500 mt-0.5">Only bookings assigned to your services appear here</p>
            </div>
        </div>
    </x-slot>

    <x-card :padding="false">
        @if($appointments->count())
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Notes</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($appointments as $appointment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-900">{{ $appointment->user->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $appointment->user->email }}</p>
                        @if($appointment->user->phone)
                        <p class="text-xs text-slate-500">{{ $appointment->user->phone }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-slate-900">{{ $appointment->service->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $appointment->service->duration_minutes }} min · ${{ number_format($appointment->service->price, 2) }}</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M j, Y') }}<br>
                        <span class="text-slate-400 text-xs">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('g:i a') }}</span>
                    </td>
                    <td class="px-6 py-4 max-w-[220px]">
                        @if($appointment->notes)
                            <p class="text-slate-500 text-xs line-clamp-2" title="{{ $appointment->notes }}">{{ $appointment->notes }}</p>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('appointments.complete', $appointment) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors">
                                Mark Complete
                            </button>
                        </form>
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
            <p class="text-slate-700 font-semibold mb-1">No upcoming appointments</p>
            <p class="text-slate-500 text-sm">New client bookings assigned to your services will appear here.</p>
        </div>
        @endif
    </x-card>
</x-app-layout>
