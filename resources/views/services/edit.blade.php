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

                @if(auth()->user()->isAdmin())
                <div>
                    <x-input-label for="provider_id" :value="__('Assigned provider')" />
                    <select id="provider_id" name="provider_id"
                            class="block w-full rounded-xl border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500/30">
                        <option value="">Choose a provider</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" @selected(old('provider_id', $service->provider_id) == $provider->id)>
                                {{ $provider->name }} · {{ $provider->email }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('provider_id')" />
                </div>
                @else
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Assigned provider</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $service->provider?->name ?? 'Unassigned' }}</p>
                </div>
                @endif

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
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-semibold text-slate-900">Location <span class="text-slate-400 font-normal">(optional)</span></p>
                        <span id="location-badge" class="{{ ($service->latitude) ? '' : 'hidden' }} inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Location set
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mb-4">Search for your address below. The pin will drop automatically — drag it to fine-tune the exact spot.</p>

                    {{-- Address search --}}
                    <div class="mb-3">
                        <x-input-label for="address" :value="__('Address')" />
                        <div class="flex gap-2 mt-1">
                            <input id="address" name="address" type="text"
                                   value="{{ old('address', $service->address) }}"
                                   placeholder="e.g. 123 Main St, New York"
                                   class="flex-1 bg-white border border-slate-300 text-slate-900 placeholder-slate-400
                                          rounded-xl px-4 py-2.5 text-sm transition-colors
                                          focus:outline-none focus:ring-2 focus:ring-slate-500/30 focus:border-slate-500" />
                            <button type="button" id="search-btn"
                                    class="shrink-0 px-4 py-2.5 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-xl transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                            <button type="button" id="locate-btn" title="Use my current location"
                                    class="shrink-0 px-3 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium rounded-xl transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                My location
                            </button>
                        </div>
                        <p id="search-error" class="hidden mt-1.5 text-xs text-red-500"></p>
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude', $service->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $service->longitude) }}">

                    <div id="service-map" class="rounded-xl overflow-hidden border border-slate-200" style="height: 300px; z-index: 1;"></div>
                    <p class="text-xs text-slate-400 mt-2">You can also click anywhere on the map or drag the pin to adjust.</p>
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
<script>
(function () {
    function initMap() {
        const ALMATY_CENTER = [43.238949, 76.889709];
        const ALMATY_BOUNDS = L.latLngBounds(
            [42.98, 76.58],
            [43.42, 77.19]
        );
        const hasExisting = {{ ($service->latitude && $service->longitude) ? 'true' : 'false' }};
        const defaultLat  = {{ old('latitude', $service->latitude ?? 43.238949) }};
        const defaultLng  = {{ old('longitude', $service->longitude ?? 76.889709) }};
        const defaultZoom = hasExisting ? 15 : 11;
        const addressInput = document.getElementById('address');
        const errorEl = document.getElementById('search-error');
        const searchBtn = document.getElementById('search-btn');
        const locateBtn = document.getElementById('locate-btn');

        const map = L.map('service-map', {
            maxBounds: ALMATY_BOUNDS,
            maxBoundsViscosity: 1.0,
            minZoom: 10,
        }).setView([defaultLat, defaultLng], defaultZoom);
        let baseLayer = L.tileLayer('https://tile{s}.maps.2gis.com/tiles?x={x}&y={y}&z={z}&v=1.1', {
            subdomains: '0123',
            attribution: '© 2GIS',
        }).addTo(map);

        function switchToFallbackTiles() {
            if (map._hasFallbackTiles) return;

            map._hasFallbackTiles = true;
            map.removeLayer(baseLayer);
            baseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                subdomains: 'abc',
                attribution: '© OpenStreetMap contributors',
            }).addTo(map);
        }

        baseLayer.once('tileerror', switchToFallbackTiles);

        L.circle(ALMATY_CENTER, {
            radius: 1800,
            color: '#475569',
            weight: 1,
            fillColor: '#94a3b8',
            fillOpacity: 0.15,
            interactive: false,
        }).addTo(map);

        const pinIcon = L.divIcon({
            html: `<div style="width:26px;height:26px;background:#475569;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 10px rgba(71,85,105,.5)"></div>`,
            iconSize: [26, 26], iconAnchor: [13, 13], className: ''
        });

        const marker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: pinIcon,
            opacity: hasExisting ? 1 : 0,
        }).addTo(map);

        function setError(message) {
            if (!message) {
                errorEl.classList.add('hidden');
                return;
            }

            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function updateInputs(latlng) {
            document.getElementById('latitude').value  = latlng.lat.toFixed(7);
            document.getElementById('longitude').value = latlng.lng.toFixed(7);
            marker.setOpacity(1);
            document.getElementById('location-badge').classList.remove('hidden');
        }

        function placePin(lat, lng, zoom) {
            const latlng = L.latLng(lat, lng);
            map.setView(latlng, zoom || 15);
            marker.setLatLng(latlng);
            updateInputs(latlng);
        }

        function formatReverseAddress(result) {
            const address = result.address || {};
            const parts = [
                [address.house_number, address.road].filter(Boolean).join(' ').trim(),
                address.suburb || address.neighbourhood || address.city_district,
                address.city || address.town || address.village,
            ].filter(Boolean);

            return parts[0] ? parts.join(', ') : result.display_name;
        }

        async function fillAddressFromCoordinates(lat, lng) {
            try {
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}&zoom=18&addressdetails=1`,
                    { headers: { 'Accept-Language': 'en' } }
                );
                const result = await res.json();

                if (result?.display_name) {
                    addressInput.value = formatReverseAddress(result);
                }
            } catch {
                // Ignore reverse-geocoding failures and keep the selected coordinates.
            }
        }

        marker.on('dragend', e => updateInputs(e.target.getLatLng()));
        map.on('click', e => placePin(e.latlng.lat, e.latlng.lng));
        setTimeout(() => map.invalidateSize(), 150);

        async function searchAddress() {
            const query = addressInput.value.trim();

            if (!query) return;

            setError('');
            searchBtn.textContent = 'Searching…';
            searchBtn.disabled = true;

            try {
                const searchQuery = `${query}, Almaty, Kazakhstan`;
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&countrycodes=kz&bounded=1&viewbox=76.58,43.42,77.19,42.98`,
                    { headers: { 'Accept-Language': 'en' } }
                );
                const results = await res.json();

                if (results.length === 0) {
                    setError('Address not found in Almaty. Try a street or landmark.');
                } else {
                    placePin(parseFloat(results[0].lat), parseFloat(results[0].lon));
                }
            } catch {
                setError('Search failed. Check your connection and try again.');
            } finally {
                searchBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg> Search`;
                searchBtn.disabled = false;
            }
        }

        searchBtn.addEventListener('click', searchAddress);
        addressInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); searchAddress(); } });

        locateBtn.addEventListener('click', () => {
            if (!navigator.geolocation) return;

            locateBtn.textContent = 'Locating…';
            locateBtn.disabled = true;
            setError('');

            navigator.geolocation.getCurrentPosition(
                async pos => {
                    const latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);
                    if (!ALMATY_BOUNDS.contains(latlng)) {
                        setError('Your current location is outside Almaty.');
                    } else {
                        placePin(latlng.lat, latlng.lng);
                        await fillAddressFromCoordinates(latlng.lat, latlng.lng);
                    }
                    locateBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> My location`;
                    locateBtn.disabled = false;
                },
                () => {
                    locateBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> My location`;
                    locateBtn.disabled = false;
                }
            );
        });
    }

    if (window.L) {
        initMap();
    } else {
        window.addEventListener('leaflet:ready', initMap, { once: true });
    }
})();
</script>
@endpush
</x-app-layout>
