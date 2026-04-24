<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Appointment */
class AppointmentCalendarSlotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->scheduled_at?->format('Y-m-d'),
            'time' => $this->scheduled_at?->format('H:i'),
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
        ];
    }
}
