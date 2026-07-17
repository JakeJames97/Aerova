<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'identifier' => $this->resource->identifier,
            'airline' => $this->resource->airline,
            'from' => $this->resource->from,
            'from_iata' => $this->resource->fromIata,
            'to' => $this->resource->to,
            'to_iata' => $this->resource->toIata,
            'departure_at' => $this->resource->departureAt,
            'arrival_at' => $this->resource->arrivalAt,
            'price' => $this->resource->price,
        ];
    }
}
