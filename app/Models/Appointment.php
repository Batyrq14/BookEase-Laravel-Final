<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AppointmentStatus;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'scheduled_at',
        'ends_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────
    //  Scopes
    // ──────────────────────────────────────────────

    /**
     * Scope: find booked appointments for a service that overlap the given [start, end) range.
     *
     * Two time ranges overlap when:  existingStart < requestedEnd AND existingEnd > requestedStart
     *
     * This pushes the overlap check to the DB engine — O(log n) with the composite index,
     * and completely eliminates the in-memory filtering that was here before.
     *
     * @param  Builder<Appointment>  $query
     * @return Builder<Appointment>
     */
    public function scopeOverlapping(Builder $query, int $serviceId, Carbon $start, Carbon $end): Builder
    {
        return $query
            ->where('service_id', $serviceId)
            ->where('status', AppointmentStatus::Booked->value)
            ->where('scheduled_at', '<', $end)
            ->where('ends_at', '>', $start);
    }
}
