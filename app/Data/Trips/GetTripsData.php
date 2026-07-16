<?php

namespace App\Data\Trips;

use App\Enums\TripStatus;
use App\Http\Requests\Trips\GetTripsRequest;

final readonly class GetTripsData
{
    public function __construct(
        public ?int $page = null,
        public ?string $country = null,
        public ?string $search = null,
        public ?TripStatus $status = null,
    ) {}

    public static function fromRequest(GetTripsRequest $request): self
    {
        return new self(
            country: $request->validated('country'),
            search: $request->validated('search'),
            status: $request->validated('status') !== null
                ? TripStatus::from($request->validated('status'))
                : null,
        );
    }
}
