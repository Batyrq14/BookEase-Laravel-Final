<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Exceptions\SlotUnavailableException;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
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

    public function create(): View
    {
        Gate::authorize('create', Appointment::class);

        $services = Service::all();

        $servicesJson = $services->map(fn($s) => [
            'id'        => $s->id,
            'name'      => $s->name,
            'address'   => $s->address,
            'latitude'  => $s->latitude  ? (float) $s->latitude  : null,
            'longitude' => $s->longitude ? (float) $s->longitude : null,
        ])->values();

        return view('appointments.create', compact('services', 'servicesJson'));
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Appointment::class);

        try {
            $this->service->book(
                userId:      $request->user()->id,
                serviceId:   (int) $request->validated('service_id'),
                scheduledAt: Carbon::parse($request->validated('scheduled_at')),
                notes:       $request->validated('notes'),
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

    // ── Admin ─────────────────────────────────────────────────────────────────

    public function adminIndex(): View
    {
        Gate::authorize('admin');

        return view('appointments.admin', [
            'appointments' => Appointment::with(['service', 'user'])
                ->latest('scheduled_at')
                ->get(),
        ]);
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        Gate::authorize('admin');

        $appointment->update(['status' => AppointmentStatus::Completed->value]);

        return back()->with('success', 'Appointment marked as completed.');
    }
}
