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
                'status' => $this->status,
                'notes' => $this->notes,
                'master' => MasterResource::make($this->whenLoaded('master')),
                'service' => ServiceResource::make($this->whenLoaded('service')),
                'schedule' => ScheduleResource::make($this->whenLoaded('schedule')),
            ]
        ];
    }
}
