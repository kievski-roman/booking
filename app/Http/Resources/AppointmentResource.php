<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'appointment' => [
                'master' => new MasterResource($this->master),
                'service' => new ServiceResource($this->service),
                'schedule' => new ScheduleResource($this->schedule),
            ]
        ];
    }
}
