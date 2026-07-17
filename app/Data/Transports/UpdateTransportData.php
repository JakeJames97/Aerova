<?php

namespace App\Data\Transports;

use App\Enums\TransportType;
use App\Http\Requests\Transports\UpdateTransportRequest;

final readonly class UpdateTransportData
{
    public function __construct(
        public TransportType $type,
        public string $from,
        public string $to,
        public string $departureAt,
        public string $arrivalAt,
        public int $price,
        public ?string $identifier = null,
        public ?string $airline = null,
        public ?string $fromIata = null,
        public ?string $toIata = null,
    ) {}

    public static function fromRequest(UpdateTransportRequest $request): self
    {
        return new self(
            type: TransportType::from($request->validated('type')),
            from: $request->validated('from'),
            to: $request->validated('to'),
            departureAt: $request->validated('departure_at'),
            arrivalAt: $request->validated('arrival_at'),
            price: $request->validated('price'),
            identifier: $request->validated('identifier'),
            airline: $request->validated('airline'),
            fromIata: $request->validated('from_iata'),
            toIata: $request->validated('to_iata'),
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'from' => $this->from,
            'to' => $this->to,
            'identifier' => $this->identifier,
            'departure_at' => $this->departureAt,
            'arrival_at' => $this->arrivalAt,
            'price' => $this->price,
            'airline' => $this->airline,
            'from_iata' => $this->fromIata,
            'to_iata' => $this->toIata,
        ];
    }
}
