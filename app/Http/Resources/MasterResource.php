<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'location' => $this->location,
            'bio' => $this->bio,
            'service' => ServiceResource::collection($this->whenLoaded('services')),
            'schedule' => ScheduleResource::collection($this->whenLoaded('schedules'))
        ];
    }
}
