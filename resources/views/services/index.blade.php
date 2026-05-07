<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Services</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ auth()->user()->isAdmin() ? 'Manage the services available for booking' : 'Services currently assigned to you' }}
                </p>
            </div>
            @can('create', \App\Models\Service::class)
            <a href="{{ route('services.create') }}">
                <x-primary-button type="button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Service
                </x-primary-button>
            </a>
            @endcan
        </div>
    </x-slot>

    <div x-data="{ deleteServiceId: null, deleteServiceName: '', deleteAction: '' }">

        {{-- Filter bar (admin only) --}}
        @can('admin')
        <form method="GET" action="{{ route('services.index') }}" class="mb-5 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                           placeholder="Service name…"
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 pl-10 pr-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-colors" />
                </div>
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Category</label>
                <select name="category_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-colors">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(($filters['category_id'] ?? '') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filter
            </button>
            @if(!empty($filters['search']) || !empty($filters['category_id']))
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </a>
            @endif
        </form>
        @endcan

        <x-card :padding="false">
            @if(isset($services) && $services->count())
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">Location</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($services as $service)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $service->name }}</p>
                            @if($service->description)
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 max-w-[200px] truncate">{{ $service->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($service->category)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-800">
                                    {{ $service->category->name }}
                                </span>
                            @else
                                <span class="text-slate-300 dark:text-slate-600 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $service->duration_minutes }} min</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-900 dark:text-white">${{ number_format($service->price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($service->provider)
                                <p class="font-medium text-slate-900 dark:text-white">{{ $service->provider->name }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $service->provider->email }}</p>
                            @else
                                <span class="text-slate-300 dark:text-slate-600 text-xs">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            @if($service->address)
                                <span class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Str::limit($service->address, 30) }}
                                </span>
                            @else
                                <span class="text-slate-300 dark:text-slate-600 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $service)
                                <a href="{{ route('services.edit', $service) }}"
                                   class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg transition-colors">
                                    Edit
                                </a>
                                @endcan
                                @can('delete', $service)
                                <button type="button"
                                        @click="deleteServiceId = {{ $service->id }}; deleteServiceName = '{{ addslashes($service->name) }}'; deleteAction = '{{ route('services.destroy', $service) }}'"
                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition-colors">
                                    Delete
                                </button>
                                @endcan
                                @cannot('update', $service)
                                    @cannot('delete', $service)
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">—</span>
                                    @endcannot
                                @endcannot
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <p class="text-slate-700 dark:text-slate-300 font-semibold mb-1">No services found</p>
                <p class="text-slate-400 dark:text-slate-500 text-sm mb-5">
                    @if(!empty($filters['search']) || !empty($filters['category_id']))
                        No services match your current filters.
                    @else
                        {{ auth()->user()->isAdmin() ? 'Add your first service to start accepting bookings' : 'Services assigned to you will appear here.' }}
                    @endif
                </p>
                @can('create', \App\Models\Service::class)
                @if(empty($filters['search']) && empty($filters['category_id']))
                <a href="{{ route('services.create') }}">
                    <x-primary-button type="button">Create first service</x-primary-button>
                </a>
                @else
                <a href="{{ route('services.index') }}">
                    <x-secondary-button type="button">Clear filters</x-secondary-button>
                </a>
                @endif
                @endcan
            </div>
            @endif
        </x-card>

        {{-- Delete confirmation modal --}}
        <div x-show="deleteServiceId !== null"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="deleteServiceId = null"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-soft-xl w-full max-w-sm p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white text-center mb-1">Delete Service</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-6">
                    Are you sure you want to delete "<span class="font-semibold text-slate-800 dark:text-slate-200" x-text="deleteServiceName"></span>"? This cannot be undone.
                </p>
                <div class="flex gap-3">
                    <button type="button" @click="deleteServiceId = null"
                            class="flex-1 px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                    <form :action="deleteAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
