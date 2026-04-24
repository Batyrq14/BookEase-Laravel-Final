<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentCalendarRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentCalendarSlotResource;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class AppointmentApiController extends Controller
{
    public function __construct(private readonly AppointmentService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Appointment::class);

        return AppointmentResource::collection(
            $this->service->getAppointmentsForUser((int) $request->user()->id),
        );
    }

    public function calendar(AppointmentCalendarRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('create', Appointment::class);

        $month = Carbon::createFromFormat('Y-m', (string) $request->validated('month'))->startOfMonth();

        return AppointmentCalendarSlotResource::collection(
            $this->service->getBookedSlotsForMonth(
                (int) $request->validated('service_id'),
                $month,
            ),
        );
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        Gate::authorize('create', Appointment::class);

        // SlotUnavailableException self-renders as 409 Conflict via its render() method.
        $appointment = $this->service->book(
            userId: $request->user()->id,
            serviceId: (int) $request->validated('service_id'),
            scheduledAt: Carbon::parse($request->validated('scheduled_at')),
            notes: $request->validated('notes'),
        );

        return AppointmentResource::make($appointment->load('service'))
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(Appointment $appointment): AppointmentResource
    {
        Gate::authorize('cancel', $appointment);

        $this->service->cancel($appointment);

        return AppointmentResource::make($appointment->refresh()->load('service'));
    }
}
