<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Exceptions\SlotUnavailableException;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentService $service) {}

    public function index(): View
    {
        Gate::authorize('viewAny', Appointment::class);

        return view('appointments.index', [
            'appointments' => $this->service->getAppointmentsForUser(auth()->id()),
        ]);
    }

    public function providerIndex(): View
    {
        Gate::authorize('provider');

        return view('appointments.provider-index', [
            'appointments' => $this->service->getUpcomingAppointmentsForProvider(auth()->id()),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Appointment::class);

        $services = Service::query()->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $servicesJson = $services->map(fn (Service $service) => $this->mapServiceForCalendar($service))->values();

        return view('appointments.create', compact('services', 'servicesJson', 'categories'));
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Appointment::class);

        try {
            $this->service->book(
                userId: $request->user()->id,
                serviceId: (int) $request->validated('service_id'),
                scheduledAt: Carbon::parse($request->validated('scheduled_at')),
                notes: $request->validated('notes'),
            );
        } catch (SlotUnavailableException $e) {
            return back()->withInput()->withErrors(['scheduled_at' => $e->getMessage()]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        Gate::authorize('cancel', $appointment);

        $this->service->cancel($appointment);

        return back()->with('success', 'Appointment cancelled.');
    }

    public function reschedule(Appointment $appointment): RedirectResponse|View
    {
        Gate::authorize('update', $appointment);

        if ($appointment->status !== AppointmentStatus::Booked->value) {
            return redirect()->route('appointments.index')
                ->with('error', 'Only booked appointments can be rescheduled.');
        }

        $appointment->load('service');

        $servicesJson = collect([
            $this->mapServiceForCalendar($appointment->service),
        ]);

        return view('appointments.reschedule', compact('appointment', 'servicesJson'));
    }

    public function performReschedule(Request $request, Appointment $appointment): RedirectResponse
    {
        Gate::authorize('update', $appointment);

        if ($appointment->status !== AppointmentStatus::Booked->value) {
            return redirect()->route('appointments.index')
                ->with('error', 'Only booked appointments can be rescheduled.');
        }

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $newDate = Carbon::parse($validated['scheduled_at']);

        if ($newDate->isWeekend()) {
            return back()->withInput()->withErrors(['scheduled_at' => 'Appointments cannot be booked on weekends.']);
        }

        try {
            $this->service->reschedule($appointment, $newDate);
        } catch (SlotUnavailableException $e) {
            return back()->withInput()->withErrors(['scheduled_at' => $e->getMessage()]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment rescheduled successfully.');
    }

    public function adminIndex(Request $request): View
    {
        Gate::authorize('admin');

        $filters = $request->only(['search', 'status']);

        $query = Appointment::with(['service.provider', 'user'])->latest('scheduled_at');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('service', fn ($s) => $s->where('name', 'like', '%' . $filters['search'] . '%'));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $stats = (clone $query)->reorder()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('appointments.admin', [
            'appointments' => $query->paginate(20)->withQueryString(),
            'filters'      => $filters,
            'stats'        => $stats,
        ]);
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        Gate::authorize('complete', $appointment);

        $appointment->update(['status' => AppointmentStatus::Completed->value]);

        return back()->with('success', 'Appointment marked as completed.');
    }

    private function mapServiceForCalendar(Service $service): array
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'price' => number_format((float) $service->price, 2, '.', ''),
            'duration_minutes' => $service->duration_minutes,
            'address' => $service->address,
            'latitude' => $service->latitude ? (float) $service->latitude : null,
            'longitude' => $service->longitude ? (float) $service->longitude : null,
            'category_id' => $service->category_id,
            'category_name' => $service->category?->name,
        ];
    }
}
