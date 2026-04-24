@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('services.index') }}"
               class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Edit Service</h1>
                <p class="text-sm text-slate-500 mt-0.5">{{ $service->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('services.update', $service) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" :value="__('Service name')" />
                    <x-text-input id="name" name="name" type="text"
                                  :value="old('name', $service->name)" required />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="3"
                              class="block w-full bg-white border border-slate-300 text-slate-900 placeholder-slate-400
                                     rounded-xl px-4 py-2.5 text-sm transition-colors resize-none
                                     focus:outline-none focus:ring-2 focus:ring-slate-500/30 focus:border-slate-500">{{ old('description', $service->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" :value="__('Price ($)')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" min="0"
                                      :value="old('price', $service->price)" required />
                        <x-input-error :messages="$errors->get('price')" />
                    </div>
                    <div>
                        <x-input-label for="duration_minutes" :value="__('Duration (min)')" />
                        <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="1"
                                      :value="old('duration_minutes', $service->duration_minutes)" required />
                        <x-input-error :messages="$errors->get('duration_minutes')" />
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <p class="text-sm font-semibold text-slate-900 mb-1">Location <span class="text-slate-400 font-normal">(optional)</span></p>
                    <p class="text-xs text-slate-400 mb-4">Drag the pin or click the map to update the location.</p>

                    <div class="mb-3">
                        <x-input-label for="address" :value="__('Address / Location name')" />
                        <x-text-input id="address" name="address" type="text"
                                      :value="old('address', $service->address)"
                                      placeholder="e.g. 123 Main St, Floor 2" />
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude', $service->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $service->longitude) }}">

                    <div id="service-map" class="rounded-xl overflow-hidden border border-slate-200" style="height: 280px; z-index: 1;"></div>
                    <p class="text-xs text-slate-400 mt-2">Drag the marker to update the exact location.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('services.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button>Save Changes</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WPaU=" crossorigin=""></script>
<script>
(function () {
    const hasCoords = {{ ($service->latitude && $service->longitude) ? 'true' : 'false' }};
    const defaultLat = {{ old('latitude', $service->latitude ?? 48.8566) }};
    const defaultLng = {{ old('longitude', $service->longitude ?? 2.3522) }};
    const zoom = hasCoords ? 14 : 3;

    const map = L.map('service-map').setView([defaultLat, defaultLng], zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const pinIcon = L.divIcon({
        html: `<div style="width:24px;height:24px;background:#4f46e5;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 8px rgba(79,70,229,.4)"></div>`,
        iconSize: [24, 24], iconAnchor: [12, 12], className: ''
    });

    const marker = L.marker([defaultLat, defaultLng], { draggable: true, icon: pinIcon }).addTo(map);

    function updateInputs(latlng) {
        document.getElementById('latitude').value  = latlng.lat.toFixed(7);
        document.getElementById('longitude').value = latlng.lng.toFixed(7);
    }

    marker.on('dragend', e => updateInputs(e.target.getLatLng()));
    map.on('click', e => { marker.setLatLng(e.latlng); updateInputs(e.latlng); });
})();
</script>
@endpush
</x-app-layout>
