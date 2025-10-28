<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vehicle */
class VehicleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pivot = $this->pivot;

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'vin' => $this->vin,
            'price' => (float) $this->price,
            'status' => $this->status,
            'assigned_role' => $pivot?->role,
            'assigned_at' => $pivot?->assigned_at,
        ];
    }
}
