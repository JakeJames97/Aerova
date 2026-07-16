<?php

namespace App\Http\Resources;

use Akaunting\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'from' => $this->resource->from,
            'to' => $this->resource->to,
            'identifier' => $this->resource->identifier,
            'departure_at' => $this->resource->departure_at->format('Y-m-d H:i:s'),
            'arrival_at' => $this->resource->arrival_at->format('Y-m-d H:i:s'),
            'price' => $this->resource->price,
            'price_formatted' => $this->resource->price !== null
                ? Money::GBP($this->resource->price)->format()
                : null,
            'airline' => $this->resource->airline,
            'from_iata' => $this->resource->from_iata,
            'to_iata' => $this->resource->to_iata,
            'current_price' => $this->resource->current_price,
            'price_checked_at' => $this->resource->price_checked_at?->format('Y-m-d H:i:s'),
        ];
    }
}
