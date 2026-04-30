<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Events\AppointmentBooked;
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

    // ──────────────────────────────────────────────
    //  Queries
    // ──────────────────────────────────────────────

    public function getAppointmentsForUser(int $userId): Collection
    {
        return $this->appointments->allForUser($userId);
    }

    public function getUpcomingAppointmentsForProvider(int $providerId): Collection
    {
        return $this->appointments->upcomingForProvider($providerId);
    }

    public function getBookedSlotsForMonth(int $serviceId, Carbon $month): Collection
    {
        $start = $month->copy()->startOfMonth()->startOfDay();
        $end = $month->copy()->endOfMonth()->endOfDay();

        return $this->appointments->bookedForServiceInRange($serviceId, $start, $end);
    }

    // ──────────────────────────────────────────────
    //  Commands
    // ──────────────────────────────────────────────

    /**
     * Book a service slot for a user.
     *
     * Automatically calculates the appointment end time from the service duration
     * and performs a database-level overlap check before persisting.
     *
     * @throws SlotUnavailableException when the requested time overlaps an existing booking.
     */
    public function book(int $userId, int $serviceId, Carbon $scheduledAt, ?string $notes = null): Appointment
    {
        $service = Service::findOrFail($serviceId);

        $endsAt = $this->calculateEndTime($scheduledAt, $service->duration_minutes);

        $this->ensureSlotAvailable($service->id, $scheduledAt, $endsAt);

        $appointment = $this->appointments->createForUser($userId, [
            'service_id' => $service->id,
            'scheduled_at' => $scheduledAt,
            'ends_at' => $endsAt,
            'status' => AppointmentStatus::Booked->value,
            'notes' => $notes,
        ]);
        AppointmentBooked::dispatch($appointment->load(['user', 'service']));

        return $appointment;
    }

    /**
     * Reschedule an existing appointment to a new time.
     *
     * Re-checks availability while excluding the current appointment from the overlap query
     * so it doesn't conflict with itself.
     *
     * @throws SlotUnavailableException
     */
    public function reschedule(Appointment $appointment, Carbon $newScheduledAt): bool
    {
        $service = $appointment->service ?? Service::findOrFail($appointment->service_id);

        $newEndsAt = $this->calculateEndTime($newScheduledAt, $service->duration_minutes);

        $this->ensureSlotAvailable($service->id, $newScheduledAt, $newEndsAt, $appointment->id);

        return $this->appointments->update($appointment, [
            'scheduled_at' => $newScheduledAt,
            'ends_at' => $newEndsAt,
        ]);
    }

    public function cancel(Appointment $appointment): bool
    {
        return $this->appointments->update($appointment, [
            'status' => AppointmentStatus::Cancelled->value,
        ]);
    }

    // ──────────────────────────────────────────────
    //  Availability Engine (private)
    // ──────────────────────────────────────────────

    /**
     * Calculate the end time of an appointment based on the service duration.
     */
    public function calculateEndTime(Carbon $start, int $durationMinutes): Carbon
    {
        return $start->copy()->addMinutes($durationMinutes);
    }

    /**
     * Ensure no booked appointment for the service overlaps the requested [start, end) range.
     *
     * This delegates to the repository which performs a single EXISTS query against the
     * composite index — O(log n) database operation, zero models hydrated.
     *
     * @throws SlotUnavailableException
     */
    private function ensureSlotAvailable(
        int $serviceId,
        Carbon $requestedStart,
        Carbon $requestedEnd,
        ?int $excludeAppointmentId = null,
    ): void {
        $hasConflict = $this->appointments->hasOverlappingAppointment(
            $serviceId,
            $requestedStart,
            $requestedEnd,
            $excludeAppointmentId,
        );

        if ($hasConflict) {
            throw SlotUnavailableException::forConflict($serviceId, $requestedStart, $requestedEnd);
        }
    }
}
