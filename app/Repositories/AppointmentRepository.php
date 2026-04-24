<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    public function allForUser(int $userId): Collection
    {
        return Appointment::with('service')
            ->where('user_id', $userId)
            ->latest('scheduled_at')
            ->get();
    }

    public function bookedForServiceOnDate(int $serviceId, Carbon $date): Collection
    {
        return Appointment::with('service')
            ->where('service_id', $serviceId)
            ->where('status', AppointmentStatus::Booked->value)
            ->whereDate('scheduled_at', $date->toDateString())
            ->get();
    }

    public function bookedForServiceInRange(int $serviceId, Carbon $start, Carbon $end): Collection
    {
        return Appointment::query()
            ->where('service_id', $serviceId)
            ->where('status', AppointmentStatus::Booked->value)
            ->whereBetween('scheduled_at', [$start, $end])
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Database-level overlap check using the `overlapping` Eloquent scope.
     *
     * The query leverages the composite index (service_id, status, scheduled_at, ends_at)
     * and returns a simple boolean — no models are hydrated.
     */
    public function hasOverlappingAppointment(
        int $serviceId,
        Carbon $start,
        Carbon $end,
        ?int $excludeAppointmentId = null,
    ): bool {
        $query = Appointment::query()->overlapping($serviceId, $start, $end);

        if ($excludeAppointmentId !== null) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    public function createForUser(int $userId, array $data): Appointment
    {
        /** @var Appointment */
        return Appointment::create([...$data, 'user_id' => $userId]);
    }
}
