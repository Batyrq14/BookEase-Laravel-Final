<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">My Schedule</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Appointments assigned to your services</p>
                </div>
            </div>
            @php
                $todayCount = $appointments->filter(fn($a) => \Carbon\Carbon::parse($a->scheduled_at)->isToday())->count();
                $weekCount  = $appointments->filter(fn($a) => \Carbon\Carbon::parse($a->scheduled_at)->isCurrentWeek())->count();
            @endphp
            <div class="hidden sm:flex items-center gap-3">
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Today</span>
                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-300 ml-1">{{ $todayCount }}</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">This Week</span>
                    <span class="text-sm font-bold text-blue-700 dark:text-blue-300 ml-1">{{ $weekCount }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <x-card :padding="false">
        @if($appointments->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider hidden md:table-cell">Notes</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors duration-150 group">
                        {{-- Client --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase shrink-0">
                                    {{ substr($appointment->user->name, 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $appointment->user->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $appointment->user->email }}</p>
                                    @if($appointment->user->phone)
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $appointment->user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        {{-- Service --}}
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-0.5 text-xs text-gray-400 dark:text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $appointment->service->duration_minutes }}m
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">·</span>
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">${{ number_format($appointment->service->price, 2) }}</span>
                            </div>
                        </td>
                        {{-- Date & Time --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span class="text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M j, Y') }}</span>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 ml-5">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('g:i a') }}</p>
                        </td>
                        {{-- Notes --}}
                        <td class="px-6 py-4 max-w-[220px] hidden md:table-cell">
                            @if($appointment->notes)
                                <p class="text-gray-500 dark:text-gray-400 text-xs line-clamp-2" title="{{ $appointment->notes }}">{{ $appointment->notes }}</p>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Action --}}
                        <td class="px-6 py-4 text-right">
                            @if($appointment->status === 'booked')
                            <form method="POST" action="{{ route('appointments.complete', $appointment) }}" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-4 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                    <svg class="w-3.5 h-3.5 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Mark Complete
                                </button>
                            </form>
                            @elseif($appointment->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-emerald-500"></span>
                                Done
                            </span>
                            @else
                            <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $appointments->links() }}
        </div>
        @endif

        @else
        <x-empty-state
            title="No upcoming appointments"
            description="New client bookings assigned to your services will appear here."
        >
            <x-slot name="icon">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </x-slot>
        </x-empty-state>
        @endif
    </x-card>
</x-app-layout>
