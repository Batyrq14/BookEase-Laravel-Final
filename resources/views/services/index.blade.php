<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Services</h1>
                <p class="text-sm text-slate-500 mt-0.5">Manage the services available for booking</p>
            </div>
            <a href="{{ route('services.create') }}">
                <x-primary-button type="button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Service
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-card :padding="false">
        @if(isset($services) && $services->count())
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($services as $service)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $service->name }}</td>
                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $service->description ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $service->duration_minutes }} min</td>
                    <td class="px-6 py-4">
                        <span class="text-indigo-600 font-semibold">${{ number_format($service->price, 2) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($service->address)
                            <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ Str::limit($service->address, 30) }}
                            </span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('services.edit', $service) }}"
                               class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('services.destroy', $service) }}" method="POST"
                                  onsubmit="return confirm('Delete this service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
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
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-slate-700 font-semibold mb-1">No services yet</p>
            <p class="text-slate-400 text-sm mb-5">Add your first service to start accepting bookings</p>
            <a href="{{ route('services.create') }}">
                <x-primary-button type="button">Create first service</x-primary-button>
            </a>
        </div>
        @endif
    </x-card>
</x-app-layout>
