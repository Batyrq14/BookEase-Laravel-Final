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
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">All Appointments</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Every booking across all clients</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                @php
                    $total     = $appointments->total();
                    $booked    = $stats->get('booked', 0);
                    $completed = $stats->get('completed', 0);
                @endphp
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Total</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white ml-1">{{ $total }}</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Active</span>
                    <span class="text-sm font-bold text-blue-700 dark:text-blue-300 ml-1">{{ $booked }}</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Completed</span>
                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-300 ml-1">{{ $completed }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Search & filter bar --}}
    <form method="GET" action="{{ route('admin.appointments.index') }}"
          class="mb-5 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Search</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Client name, email, or service..."
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 pl-10 pr-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-colors" />
            </div>
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Status</label>
            <select name="status"
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-colors">
                <option value="">All statuses</option>
                <option value="booked" @selected(($filters['status'] ?? '') === 'booked')>Booked</option>
                <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelled</option>
            </select>
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
        </button>
        @if(!empty($filters['search']) || !empty($filters['status']))
        <a href="{{ route('admin.appointments.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear
        </a>
        @endif
    </form>

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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status</th>
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
                        <td class="px-6 py-4 max-w-[180px] hidden md:table-cell">
                            @if($appointment->notes)
                                <p class="text-gray-500 dark:text-gray-400 text-xs line-clamp-2" title="{{ $appointment->notes }}">{{ $appointment->notes }}</p>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @php
                                $badgeVariant = match($appointment->status) {
                                    'booked'    => 'blue',
                                    'cancelled' => 'red',
                                    'completed' => 'emerald',
                                    default     => 'default',
                                };
                            @endphp
                            <x-badge :variant="$badgeVariant" hasDot>{{ ucfirst($appointment->status) }}</x-badge>
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
            :title="(!empty($filters['search']) || !empty($filters['status'])) ? 'No matching appointments' : 'No appointments yet'"
            :description="(!empty($filters['search']) || !empty($filters['status'])) ? 'Try adjusting your search or filter.' : 'Bookings will appear here once clients start scheduling.'"
        >
            <x-slot name="icon">
                <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </x-slot>
        </x-empty-state>
        @endif
    </x-card>
</x-app-layout>
