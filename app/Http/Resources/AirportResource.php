<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AirportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'iata' => $this->resource->iata,
            'name' => $this->resource->name,
        ];
    }
}
