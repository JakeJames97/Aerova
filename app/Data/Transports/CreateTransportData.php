<?php

namespace App\Data\Transports;

use App\Enums\TransportType;
use App\Http\Requests\Transports\CreateTransportRequest;

final readonly class CreateTransportData
{
    public function __construct(
        public TransportType $type,
        public string $from,
        public string $to,
        public ?string $identifier = null,
        public ?string $departureAt = null,
        public ?string $arrivalAt = null,
        public ?int $price = null,
        public ?string $airline = null,
        public ?string $fromIata = null,
        public ?string $toIata = null,
    ) {}

    public static function fromRequest(CreateTransportRequest $request): self
    {
        return new self(
            type: TransportType::from($request->validated('type')),
            from: $request->validated('from'),
            to: $request->validated('to'),
            identifier: $request->validated('identifier'),
            departureAt: $request->validated('departure_at'),
            arrivalAt: $request->validated('arrival_at'),
            price: $request->validated('price'),
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
