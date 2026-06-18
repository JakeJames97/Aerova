<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'start_date' => $this->resource->start_date?->toDateString(),
            'end_date' => $this->resource->end_date?->toDateString(),
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at->toDateTimeString(),
            'destinations_count' => $this->whenCounted('destinations'),
            'destinations' => DestinationResource::collection($this->whenLoaded('destinations')),
        ];
    }
}
