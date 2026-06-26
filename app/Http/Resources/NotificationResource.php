<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->data['type'] ?? null,
            'data' => $this->resource->data,
            'read_at' => $this->resource->read_at,
            'created_at' => $this->resource->created_at,
        ];
    }
}
