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

    /**
     * Return booked appointments for a service on a specific date,
     * eager-loading service (needed for duration_minutes).
     */
    public function bookedForServiceOnDate(int $serviceId, Carbon $date): Collection;

    public function createForUser(int $userId, array $data): Appointment;
}
