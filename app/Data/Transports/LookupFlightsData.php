<?php

namespace App\Data\Transports;

use App\Http\Requests\Transports\LookupFlightsRequest;

final readonly class LookupFlightsData
{
    public function __construct(
        public string $date,
        public string $fromIata,
        public string $toIata,
    ) {}

    public static function fromRequest(LookupFlightsRequest $request): self
    {
        return new self(
            date: $request->validated('date'),
            fromIata: $request->validated('from_iata'),
            toIata: $request->validated('to_iata'),
        );
    }
}
