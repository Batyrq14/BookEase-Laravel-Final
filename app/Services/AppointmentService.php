<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Exceptions\SlotUnavailableException;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AppointmentService
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointments,
    ) {}

    public function getAppointmentsForUser(int $userId): Collection
    {
        return $this->appointments->allForUser($userId);
    }

    public function getBookedSlotsForMonth(int $serviceId, Carbon $month): Collection
    {
        $start = $month->copy()->startOfMonth()->startOfDay();
        $end = $month->copy()->endOfMonth()->endOfDay();

        return $this->appointments->bookedForServiceInRange($serviceId, $start, $end);
    }

    /**
     * Book a service slot for a user.
     *
     * @throws SlotUnavailableException when the requested time overlaps an existing booking.
     */
    public function book(int $userId, int $serviceId, Carbon $scheduledAt, ?string $notes = null): Appointment
    {
        $service = Service::findOrFail($serviceId);

        $this->ensureSlotAvailable($service, $scheduledAt);

        return $this->appointments->createForUser($userId, [
            'service_id' => $service->id,
            'scheduled_at' => $scheduledAt,
            'status' => AppointmentStatus::Booked->value,
            'notes' => $notes,
        ]);
    }

    public function cancel(Appointment $appointment): bool
    {
        return $this->appointments->update($appointment, [
            'status' => AppointmentStatus::Cancelled->value,
        ]);
    }

    /**
     * @throws SlotUnavailableException
     */
    private function ensureSlotAvailable(Service $service, Carbon $requestedStart): void
    {
        $requestedEnd = $requestedStart->copy()->addMinutes($service->duration_minutes);

        $conflicting = $this->appointments
            ->bookedForServiceOnDate($service->id, $requestedStart)
            ->first(function (Appointment $existing) use ($requestedStart, $requestedEnd): bool {
                $existingStart = Carbon::parse($existing->scheduled_at);
                $existingEnd = $existingStart->copy()->addMinutes($existing->service->duration_minutes);

                return $requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart);
            });

        if ($conflicting !== null) {
            throw new SlotUnavailableException(
                __('This time slot conflicts with an existing booking.')
            );
        }
    }
}
