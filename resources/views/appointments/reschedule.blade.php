<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('appointments.index') }}"
               class="rounded-xl border border-gray-200 p-2 text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-900">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-950">Reschedule Appointment</h1>
                <p class="mt-0.5 text-sm text-gray-500">Pick a new date and time for <span class="font-medium text-gray-700">{{ $appointment->service->name }}</span>.</p>
            </div>
        </div>
    </x-slot>

    <div
        class="mx-auto max-w-6xl"
        x-data="bookingCalendar({
            services: @js($servicesJson),
            availabilityUrl: @js(route('appointments.calendar')),
            initialServiceId: @js($appointment->service->id),
            initialScheduledAt: @js(old('scheduled_at', $appointment->scheduled_at->format('Y-m-d H:i:s'))),
        })"
        x-init="init()"
    >
        <x-card class="rounded-2xl border-gray-200 shadow-none">
            <form method="POST" action="{{ route('appointments.reschedule.update', $appointment) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Service display (read-only) --}}
                <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50/80 px-5 py-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.24em] text-gray-400">Service</p>
                        <p class="mt-1 text-base font-semibold text-gray-950">{{ $appointment->service->name }}</p>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        <p>{{ $appointment->service->duration_minutes }} min</p>
                        <p class="mt-1">${{ number_format($appointment->service->price, 2) }}</p>
                    </div>
                </div>

                {{-- Location map (if service has one) --}}
                <div x-show="hasLocation" x-cloak class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                    <div class="flex items-center gap-2 border-b border-gray-200 bg-gray-50 px-4 py-3">
                        <svg class="h-4 w-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-700" x-text="selectedAddress || 'Service location'"></p>
                    </div>
                    <div id="location-map" style="height: 220px; z-index: 1;"></div>
                </div>

                <div x-show="selectedId && !hasLocation" x-cloak class="flex items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <div class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Add a location in Services first so clients can see where to go.</span>
                    </div>
                    @can('admin')
                    <a x-bind:href="`/services/${selectedId}/edit`"
                       class="shrink-0 rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-xs font-medium text-amber-900 transition hover:bg-amber-100">
                        Edit service
                    </a>
                    @endcan
                </div>

                <input type="hidden" id="scheduled_at" name="scheduled_at" :value="scheduledAtValue">

                <div class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(320px,0.95fr)]">
                    {{-- Calendar --}}
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-[0.24em] text-gray-400">Calendar</p>
                                <h2 class="mt-2 text-lg font-semibold text-gray-950" x-text="currentMonthLabel"></h2>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="changeMonth(-1)" :disabled="!canGoToPreviousMonth"
                                    class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:pointer-events-none disabled:bg-gray-50 disabled:text-gray-300">
                                    Prev
                                </button>
                                <button type="button" @click="changeMonth(1)"
                                    class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                    Next
                                </button>
                            </div>
                        </div>

                        <div class="mt-6" x-show="loading" x-cloak>
                            <div class="grid grid-cols-7 gap-2">
                                <template x-for="index in 35" :key="index">
                                    <div class="h-20 animate-pulse rounded-xl border border-gray-100 bg-gray-100/70"></div>
                                </template>
                            </div>
                        </div>

                        <div x-show="!loading" x-cloak>
                            <div class="grid grid-cols-7 gap-2 text-center text-xs font-medium uppercase tracking-[0.18em] text-gray-400">
                                <template x-for="label in weekdayLabels" :key="label">
                                    <div class="py-2" x-text="label"></div>
                                </template>
                            </div>

                            <div class="mt-2 grid grid-cols-7 gap-2">
                                <template x-for="day in calendarDays" :key="day.date">
                                    <button type="button" @click="selectDate(day.date)" :disabled="!day.selectable"
                                        class="group min-h-[84px] rounded-xl border px-3 py-3 text-left transition"
                                        :class="dateButtonClasses(day)">
                                        <div class="flex items-start justify-between gap-2">
                                            <span class="text-sm font-semibold" x-text="day.dayNumber"></span>
                                            <span x-show="day.isToday" x-cloak
                                                class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium uppercase tracking-[0.16em] text-gray-500">
                                                Today
                                            </span>
                                        </div>
                                        <p class="mt-5 text-xs"
                                           x-text="day.isPast ? 'Past' : (day.isWeekend ? 'Closed' : (day.isFull ? 'Taken' : (day.inCurrentMonth ? 'Available' : '--')))">
                                        </p>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div x-show="fetchError" x-cloak
                            class="mt-4 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-500"
                            x-text="fetchError"></div>

                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <span class="h-4 w-4 rounded-md border border-gray-200 bg-white"></span>
                                <span>Available</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-4 w-4 rounded-md border border-gray-200 bg-gray-50"></span>
                                <span>Taken</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-4 w-4 rounded-md bg-black"></span>
                                <span>Selected</span>
                            </div>
                        </div>
                    </section>

                    {{-- Time slots --}}
                    <aside class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6">
                        <div class="border-b border-gray-100 pb-5">
                            <p class="text-xs font-medium uppercase tracking-[0.24em] text-gray-400">Time slots</p>
                            <h2 class="mt-2 text-lg font-semibold text-gray-950">Choose a start time</h2>
                            <p class="mt-2 text-sm text-gray-500" x-text="selectedDateLabel"></p>
                        </div>

                        <div class="mt-5" x-show="loading" x-cloak>
                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="index in 8" :key="index">
                                    <div class="h-12 animate-pulse rounded-xl border border-gray-100 bg-gray-100/70"></div>
                                </template>
                            </div>
                        </div>

                        <div x-show="!loading" x-cloak>
                            <div x-show="!selectedDate" x-cloak
                                class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500">
                                Pick a date from the calendar to reveal available times.
                            </div>

                            <div x-show="selectedDate" x-cloak class="space-y-4">
                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                    <template x-for="slot in timeSlots" :key="slot">
                                        <button type="button" @click="selectSlot(slot)" :disabled="isSlotDisabled(slot)"
                                            class="rounded-xl border px-4 py-3 text-sm font-medium transition"
                                            :class="slotButtonClasses(slot)">
                                            <span x-text="formatTime(slot)"></span>
                                        </button>
                                    </template>
                                </div>

                                <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
                                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-gray-400">New time</p>
                                    <p class="mt-2 text-base font-semibold text-gray-950" x-text="selectionSummary"></p>
                                    <p class="mt-1 text-sm text-gray-500" x-text="selectedService ? `${selectedService.duration_minutes} minute session` : ''"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-error :messages="$errors->get('scheduled_at')" />
                        </div>
                    </aside>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-2">
                    <a href="{{ route('appointments.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button x-bind:disabled="!scheduledAtValue">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Confirm Reschedule
                    </x-primary-button>
                </div>
            </form>
        </x-card>
    </div>

@push('scripts')
<script>
function bookingCalendar(config) {
    return {
        services: config.services ?? [],
        availabilityUrl: config.availabilityUrl,
        selectedId: config.initialServiceId ? String(config.initialServiceId) : '',
        selectedDate: '',
        selectedSlot: '',
        bookedSlots: {},
        loading: false,
        fetchError: '',
        map: null,
        marker: null,
        monthCursor: null,
        todayKey: '',
        weekdayLabels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        timeSlots: [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
        ],

        init() {
            const now = new Date();
            this.todayKey = this.toDateKey(now);

            if (!this.selectedId && this.services.length > 0) {
                this.selectedId = String(this.services[0].id);
            }

            const initialDateTime = this.parseScheduledAt(config.initialScheduledAt);

            if (initialDateTime) {
                this.selectedDate = initialDateTime.date;
                this.selectedSlot = initialDateTime.time;
                this.monthCursor = new Date(initialDateTime.year, initialDateTime.month - 1, 1);
            } else {
                this.monthCursor = new Date(now.getFullYear(), now.getMonth(), 1);
            }

            this.onServiceChange();
        },

        get selectedService() {
            return this.services.find((s) => String(s.id) === String(this.selectedId)) || null;
        },
        get selectedServiceDuration() { return Number(this.selectedService?.duration_minutes || 30); },
        get hasLocation() { return !!(this.selectedService?.latitude && this.selectedService?.longitude); },
        get selectedAddress() { return this.selectedService?.address || ''; },

        get monthKey() {
            if (!this.monthCursor) return '';
            return `${this.monthCursor.getFullYear()}-${this.pad(this.monthCursor.getMonth() + 1)}`;
        },
        get currentMonthLabel() {
            if (!this.monthCursor) return '';
            return this.monthCursor.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
        },
        get canGoToPreviousMonth() {
            if (!this.monthCursor) return false;
            const cur = new Date(); cur.setDate(1); cur.setHours(0,0,0,0);
            return this.monthCursor > cur;
        },
        get selectedDateLabel() {
            return this.selectedDate ? this.formatDate(this.selectedDate) : 'Select a date to unlock the available times.';
        },
        get scheduledAtValue() {
            return (this.selectedDate && this.selectedSlot) ? `${this.selectedDate} ${this.selectedSlot}:00` : '';
        },
        get selectionSummary() {
            if (!this.selectedDate || !this.selectedSlot) return 'No time selected yet';
            return `${this.formatDate(this.selectedDate)} at ${this.formatTime(this.selectedSlot)}`;
        },
        get calendarDays() {
            if (!this.monthCursor) return [];
            const firstOfMonth = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth(), 1);
            const gridStart = new Date(firstOfMonth);
            gridStart.setDate(firstOfMonth.getDate() - firstOfMonth.getDay());

            return Array.from({ length: 42 }, (_, index) => {
                const date = new Date(gridStart);
                date.setDate(gridStart.getDate() + index);
                const dateKey = this.toDateKey(date);
                const inCurrentMonth = date.getMonth() === firstOfMonth.getMonth();
                const isWeekend = date.getDay() === 0 || date.getDay() === 6;
                const isPast = dateKey < this.todayKey;
                const isFull = inCurrentMonth && !isPast && !isWeekend && this.selectedService
                    ? this.dayHasNoAvailableSlots(dateKey) : false;

                return {
                    date: dateKey, dayNumber: date.getDate(), inCurrentMonth,
                    isWeekend, isPast, isFull,
                    isToday: dateKey === this.todayKey,
                    isSelected: this.selectedDate === dateKey,
                    selectable: Boolean(this.selectedService) && inCurrentMonth && !isWeekend && !isPast && !isFull,
                };
            });
        },

        async onServiceChange() {
            this.selectedSlot = '';
            await this.fetchBookedSlots();
            this.$nextTick(() => this.syncMap());
        },
        async changeMonth(offset) {
            if (!this.monthCursor) return;
            const candidate = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() + offset, 1);
            const cur = new Date(); cur.setDate(1); cur.setHours(0,0,0,0);
            if (candidate < cur) return;
            this.monthCursor = candidate;
            if (this.selectedDate && !this.selectedDate.startsWith(this.monthKey)) {
                this.selectedDate = ''; this.selectedSlot = '';
            }
            await this.fetchBookedSlots();
        },
        async fetchBookedSlots() {
            if (!this.selectedService || !this.monthKey) { this.bookedSlots = {}; return; }
            this.loading = true; this.fetchError = '';
            try {
                const params = new URLSearchParams({ service_id: this.selectedId, month: this.monthKey });
                const response = await fetch(`${this.availabilityUrl}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!response.ok) throw new Error('Unable to load the booking calendar right now.');
                const payload = await response.json();
                const slots = Array.isArray(payload.data) ? payload.data : [];
                this.bookedSlots = slots.reduce((carry, slot) => {
                    if (!carry[slot.date]) carry[slot.date] = [];
                    carry[slot.date].push(slot);
                    return carry;
                }, {});
                this.syncSelection();
            } catch (error) {
                this.bookedSlots = {};
                this.fetchError = error.message || 'Unable to load the booking calendar right now.';
            } finally {
                this.loading = false;
            }
        },
        selectDate(dateKey) {
            if (!this.isDateSelectable(dateKey)) return;
            if (this.selectedDate !== dateKey) this.selectedSlot = '';
            this.selectedDate = dateKey;
            if (this.selectedSlot && this.isSlotDisabled(this.selectedSlot)) this.selectedSlot = '';
        },
        selectSlot(time) {
            if (this.isSlotDisabled(time)) return;
            this.selectedSlot = this.selectedSlot === time ? '' : time;
        },
        isDateSelectable(dateKey) {
            const day = this.calendarDays.find((e) => e.date === dateKey);
            return Boolean(day?.selectable);
        },
        dayHasNoAvailableSlots(dateKey) {
            return this.timeSlots.every((slot) => this.isSlotUnavailableForDate(dateKey, slot));
        },
        isSlotUnavailableForDate(dateKey, time) {
            return this.isPastSlot(dateKey, time) || this.isSlotBooked(dateKey, time);
        },
        isSlotDisabled(time) {
            if (!this.selectedDate) return true;
            return this.isSlotUnavailableForDate(this.selectedDate, time);
        },
        isSlotBooked(dateKey, time) {
            const daySlots = this.bookedSlots[dateKey] || [];
            const requestedStart = this.toDateTime(dateKey, time);
            const requestedEnd = new Date(requestedStart.getTime() + this.selectedServiceDuration * 60000);
            return daySlots.some((slot) => {
                const existingStart = this.toDateTime(slot.date, slot.time);
                const existingEnd = new Date(existingStart.getTime() + this.selectedServiceDuration * 60000);
                return requestedStart < existingEnd && requestedEnd > existingStart;
            });
        },
        isPastSlot(dateKey, time) { return this.toDateTime(dateKey, time) <= new Date(); },
        dateButtonClasses(day) {
            return {
                'border-gray-200 bg-white text-gray-900 hover:bg-gray-50': day.selectable && !day.isSelected,
                'border-black bg-black text-white': day.isSelected,
                'border-gray-200 bg-gray-50 text-gray-400 pointer-events-none': !day.selectable,
                'ring-1 ring-gray-300': day.isToday && !day.isSelected,
            };
        },
        slotButtonClasses(time) {
            return {
                'border-gray-200 bg-white text-gray-900 hover:bg-gray-50': !this.isSlotDisabled(time) && this.selectedSlot !== time,
                'border-black bg-black text-white': this.selectedSlot === time,
                'border-gray-200 bg-gray-50 text-gray-400 pointer-events-none': this.isSlotDisabled(time),
            };
        },
        syncSelection() {
            if (!this.selectedDate) return;
            if (!this.isDateSelectable(this.selectedDate)) { this.selectedDate = ''; this.selectedSlot = ''; return; }
            if (this.selectedSlot && this.isSlotDisabled(this.selectedSlot)) this.selectedSlot = '';
        },
        syncMap() {
            if (!this.hasLocation) return;
            this.$nextTick(() => {
                const lat = this.selectedService.latitude;
                const lng = this.selectedService.longitude;
                if (!this.map) {
                    this.map = L.map('location-map').setView([lat, lng], 15);
                    let baseLayer = L.tileLayer('https://tile{s}.maps.2gis.com/tiles?x={x}&y={y}&z={z}&v=1.1', {
                        subdomains: '0123',
                        attribution: '© 2GIS',
                    }).addTo(this.map);

                    const switchToFallbackTiles = () => {
                        if (this.map._hasFallbackTiles) return;

                        this.map._hasFallbackTiles = true;
                        this.map.removeLayer(baseLayer);
                        baseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            subdomains: 'abc',
                            attribution: '© OpenStreetMap contributors',
                        }).addTo(this.map);
                    };

                    baseLayer.once('tileerror', switchToFallbackTiles);

                    const icon = L.divIcon({
                        html: '<div style="width:22px;height:22px;background:#111827;border:3px solid #fff;border-radius:50%;box-shadow:0 1px 3px rgba(17,24,39,.18)"></div>',
                        iconSize: [22, 22], iconAnchor: [11, 11], className: '',
                    });
                    this.marker = L.marker([lat, lng], { icon }).addTo(this.map);
                } else {
                    this.map.setView([lat, lng], 15);
                    this.marker.setLatLng([lat, lng]);
                }
                setTimeout(() => this.map.invalidateSize(), 150);
            });
        },
        parseScheduledAt(value) {
            if (!value) return null;
            const normalised = String(value).replace(' ', 'T');
            const [datePart, timePart] = normalised.split('T');
            if (!datePart || !timePart) return null;
            const [year, month, day] = datePart.split('-').map(Number);
            const time = timePart.slice(0, 5);
            if (!year || !month || !day || time.length !== 5) return null;
            return { year, month, day, date: `${year}-${this.pad(month)}-${this.pad(day)}`, time };
        },
        formatDate(dateKey) {
            const [year, month, day] = dateKey.split('-').map(Number);
            return new Date(year, month - 1, day).toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric' });
        },
        formatTime(time) {
            const [hours, minutes] = time.split(':').map(Number);
            return new Date(2000, 0, 1, hours, minutes).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },
        toDateKey(date) { return `${date.getFullYear()}-${this.pad(date.getMonth() + 1)}-${this.pad(date.getDate())}`; },
        toDateTime(dateKey, time) {
            const [year, month, day] = dateKey.split('-').map(Number);
            const [hours, minutes] = time.split(':').map(Number);
            return new Date(year, month - 1, day, hours, minutes, 0, 0);
        },
        pad(value) { return String(value).padStart(2, '0'); },
    };
}
</script>
@endpush
</x-app-layout>
