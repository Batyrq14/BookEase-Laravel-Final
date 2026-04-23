@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('appointments.index') }}"
               class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Book Appointment</h1>
                <p class="text-sm text-slate-500 mt-0.5">Choose a service and pick your time</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-xl" x-data="bookingForm(@json($servicesJson))">

        <x-card>
            <form method="POST" action="{{ route('appointments.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="service_id" :value="__('Select service')" />
                    <select id="service_id" name="service_id" required
                            x-model="selectedId"
                            @change="onServiceChange()"
                            class="block w-full bg-white border border-slate-300 text-slate-900 rounded-xl px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
                        <option value="" class="text-slate-400">— Choose a service —</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}"
                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} · {{ $service->duration_minutes }} min · ${{ number_format($service->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('service_id')" />
                </div>

                <div x-show="hasLocation" x-cloak class="rounded-xl overflow-hidden border border-slate-200">
                    <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 border-b border-slate-200">
                        <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm text-slate-700 font-medium" x-text="selectedAddress || 'Service location'"></p>
                    </div>
                    <div id="location-map" style="height: 220px; z-index: 1;"></div>
                </div>

                <div x-show="selectedId && !hasLocation" x-cloak
                     class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    No location set for this service.
                </div>

                <div>
                    <x-input-label for="scheduled_at" :value="__('Date & time')" />
                    <x-text-input id="scheduled_at" name="scheduled_at" type="datetime-local"
                                  :value="old('scheduled_at')" required />
                    <x-input-error :messages="$errors->get('scheduled_at')" />
                </div>

                <div>
                    <x-input-label for="notes" :value="__('Notes / Special requests')" />
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Allergies, preferences, anything we should know..."
                              class="block w-full bg-white border border-slate-300 text-slate-900 placeholder-slate-400
                                     rounded-xl px-4 py-2.5 text-sm transition-colors resize-none
                                     focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('appointments.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Confirm Booking
                    </x-primary-button>
                </div>
            </form>
        </x-card>
    </div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WPaU=" crossorigin=""></script>
<script>
function bookingForm(services) {
    return {
        services,
        selectedId: '{{ old('service_id', '') }}',
        map: null,
        marker: null,

        get selectedService() {
            return this.services.find(s => s.id == this.selectedId) || null;
        },
        get hasLocation() {
            return !!(this.selectedService?.latitude && this.selectedService?.longitude);
        },
        get selectedAddress() {
            return this.selectedService?.address || '';
        },

        onServiceChange() {
            this.$nextTick(() => {
                if (!this.hasLocation) return;
                const lat = this.selectedService.latitude;
                const lng = this.selectedService.longitude;

                if (!this.map) {
                    this.map = L.map('location-map').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(this.map);

                    const icon = L.divIcon({
                        html: `<div style="width:22px;height:22px;background:#4f46e5;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 8px rgba(79,70,229,.4)"></div>`,
                        iconSize: [22, 22], iconAnchor: [11, 11], className: ''
                    });
                    this.marker = L.marker([lat, lng], { icon }).addTo(this.map);
                } else {
                    this.map.setView([lat, lng], 15);
                    this.marker.setLatLng([lat, lng]);
                }

                setTimeout(() => this.map.invalidateSize(), 150);
            });
        },

        init() {
            if (this.selectedId) this.onServiceChange();
        }
    };
}
</script>
@endpush
</x-app-layout>
