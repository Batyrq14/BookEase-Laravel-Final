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
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">My Appointments</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">View and manage your bookings</p>
                </div>
            </div>
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Book Appointment
            </a>
        </div>
    </x-slot>

    <div x-data="{ cancelId: null, cancelService: '', cancelAction: '' }">
        <x-card :padding="false">
            @if(isset($appointments) && $appointments->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider hidden md:table-cell">Notes</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach ($appointments as $appointment)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors duration-150">
                            {{-- Service --}}
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $appointment->service->name }}</p>
                                @if($appointment->service->provider)
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $appointment->service->provider->name }}</p>
                                @endif
                            </td>
                            {{-- Date & Time --}}
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M j, Y') }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('g:i a') }}</p>
                            </td>
                            {{-- Notes --}}
                            <td class="px-6 py-4 max-w-[180px] hidden md:table-cell">
                                @if($appointment->notes)
                                    <p class="text-slate-500 dark:text-slate-400 text-xs line-clamp-2">{{ $appointment->notes }}</p>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">—</span>
                                @endif
                            </td>
                            {{-- Status Badge --}}
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
                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($appointment->status === 'booked')
                                    <a href="{{ route('appointments.reschedule', $appointment) }}"
                                       class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg transition-colors">
                                        Reschedule
                                    </a>
                                    <button type="button"
                                            @click="cancelId = {{ $appointment->id }}; cancelService = '{{ addslashes($appointment->service->name) }}'; cancelAction = '{{ route('appointments.destroy', $appointment) }}'"
                                            class="px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                    @elseif($appointment->status === 'cancelled')
                                    <a href="{{ route('appointments.create', ['service_id' => $appointment->service_id]) }}"
                                       class="px-3 py-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-900/20 hover:bg-brand-100 dark:hover:bg-brand-900/40 rounded-lg transition-colors">
                                        Book Again
                                    </a>
                                    @else
                                    <span class="text-slate-300 dark:text-slate-600 text-xs">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $appointments->links() }}
            </div>
            @endif

            @else
            <x-empty-state
                title="No appointments yet"
                description="Book your first appointment and we'll help you stay organized."
                actionLabel="Book Your First Appointment"
                actionUrl="{{ route('appointments.create') }}"
            >
                <x-slot name="icon">
                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </x-slot>
            </x-empty-state>
            @endif
        </x-card>

        {{-- Cancel confirmation modal --}}
        <div x-show="cancelId !== null"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="cancelId = null"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-soft-xl w-full max-w-sm p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white text-center mb-1">Cancel Appointment</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-6">
                    Cancel your booking for "<span class="font-semibold text-slate-800 dark:text-slate-200" x-text="cancelService"></span>"? This cannot be undone.
                </p>
                <div class="flex gap-3">
                    <button type="button" @click="cancelId = null"
                            class="flex-1 px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Keep It
                    </button>
                    <form :action="cancelAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                            Yes, Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
