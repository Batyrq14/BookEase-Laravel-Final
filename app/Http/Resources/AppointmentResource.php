<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Appointment */
class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $status = AppointmentStatus::tryFrom((string) $this->status);

        return [
            'id' => $this->id,
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'scheduled_at_display' => $this->scheduled_at?->format('Y-m-d H:i'),
            'status' => $this->status,
            'status_label' => $status?->label() ?? ucfirst((string) $this->status),
            'notes' => $this->notes,
            'service' => ServiceResource::make($this->whenLoaded('service')),
        ];
    }
}
