<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Service */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'price' => number_format((float) $this->price, 2, '.', ''),
            'location' => $this->when(
                $this->address !== null || $this->latitude !== null || $this->longitude !== null,
                fn (): array => [
                    'address' => $this->address,
                    'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
                    'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
                ],
            ),
        ];
    }
}
