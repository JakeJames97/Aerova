<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'city' => $this->resource->city,
            'country_code' => $this->resource->country_code,
            'budget' => $this->resource->budget,
            'arrival_date' => $this->resource->arrival_date?->toDateString(),
            'departure_date' => $this->resource->departure_date?->toDateString(),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
