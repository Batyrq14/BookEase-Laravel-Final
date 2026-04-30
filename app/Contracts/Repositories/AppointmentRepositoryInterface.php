<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

interface AppointmentRepositoryInterface extends RepositoryInterface
{
    /** Return all appointments for a given user, eager-loading service. */
    public function allForUser(int $userId): Collection;

    /** Return upcoming booked appointments assigned to a provider. */
    public function upcomingForProvider(int $providerId): Collection;

    /**
     * Return booked appointments for a service on a specific date,
     * eager-loading service (needed for duration_minutes).
     */
    public function bookedForServiceOnDate(int $serviceId, Carbon $date): Collection;

    /** Return booked appointments for a service in a date range. */
    public function bookedForServiceInRange(int $serviceId, Carbon $start, Carbon $end): Collection;

    /**
     * Check whether any existing booked appointment for the given service
     * overlaps the [start, end) time range.
     *
     * Uses a database-level query to avoid race conditions and O(n) scanning.
     *
     * @param  int|null  $excludeAppointmentId  Appointment ID to exclude (for reschedule operations).
     */
    public function hasOverlappingAppointment(int $serviceId, Carbon $start, Carbon $end, ?int $excludeAppointmentId = null): bool;

    public function createForUser(int $userId, array $data): Appointment;
}
