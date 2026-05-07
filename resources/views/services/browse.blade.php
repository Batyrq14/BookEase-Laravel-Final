<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Browse Services</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Find and book a service that suits you</p>
            </div>
            <a href="{{ route('appointments.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Book Appointment
            </a>
        </div>
    </x-slot>

    {{-- Search & filter bar --}}
    <form method="GET" action="{{ route('services.browse') }}"
          class="mb-6 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Search</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Search services..."
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
        <a href="{{ route('services.browse') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear
        </a>
        @endif
    </form>

    @if($services->count())
    {{-- Results count --}}
    <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-4">
        {{ $services->count() }} {{ Str::plural('service', $services->count()) }} found
        @if(!empty($filters['search']) || !empty($filters['category_id']))
            &mdash; filtered
        @endif
    </p>

    {{-- Service cards grid --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($services as $service)
        <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md hover:border-brand-200 dark:hover:border-brand-700 transition-all duration-300">
            {{-- Card header with category color accent --}}
            <div class="h-1.5 bg-gradient-to-r from-brand-500 to-brand-400"></div>

            <div class="p-5">
                {{-- Category badge --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    @if($service->category)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-brand-50 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-800">
                        {{ $service->category->name }}
                    </span>
                    @else
                    <span></span>
                    @endif
                    <span class="text-lg font-extrabold text-slate-900 dark:text-white">${{ number_format($service->price, 2) }}</span>
                </div>

                {{-- Service name & description --}}
                <h3 class="font-bold text-slate-900 dark:text-white text-base leading-snug mb-1.5">
                    {{ $service->name }}
                </h3>
                @if($service->description)
                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4">
                    {{ $service->description }}
                </p>
                @else
                <div class="mb-4"></div>
                @endif

                {{-- Meta info --}}
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                        <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $service->duration_minutes }} min
                    </div>
                    @if($service->address)
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                        <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ Str::limit($service->address, 28) }}
                    </div>
                    @endif
                </div>

                {{-- Provider --}}
                @if($service->provider)
                <div class="flex items-center gap-2.5 mb-5 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-brand-600 dark:bg-brand-500 flex items-center justify-center text-xs font-bold text-white shrink-0">
                        {{ strtoupper(substr($service->provider->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $service->provider->name }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Provider</p>
                    </div>
                </div>
                @endif

                {{-- CTA --}}
                <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}"
                   class="block w-full text-center px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    Book This Service
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <p class="text-slate-800 dark:text-slate-200 font-semibold text-base mb-1">
            @if(!empty($filters['search']) || !empty($filters['category_id']))
                No services match your filters
            @else
                No services available yet
            @endif
        </p>
        <p class="text-slate-400 dark:text-slate-500 text-sm mb-6">
            @if(!empty($filters['search']) || !empty($filters['category_id']))
                Try adjusting your search or clearing the filters.
            @else
                Check back soon — services will appear here once they are added.
            @endif
        </p>
        @if(!empty($filters['search']) || !empty($filters['category_id']))
        <a href="{{ route('services.browse') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200">
            Clear filters
        </a>
        @endif
    </div>
    @endif
</x-app-layout>
