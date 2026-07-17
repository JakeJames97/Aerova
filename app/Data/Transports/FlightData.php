<?php

namespace App\Data\Transports;

final readonly class FlightData
{
    public function __construct(
        public string $identifier,
        public string $airline,
        public string $from,
        public string $fromIata,
        public string $to,
        public string $toIata,
        public string $departureAt,
        public string $arrivalAt,
        public ?int $price = null,
    ) {}

    public static function fromItinerary(array $itinerary): self
    {
        $segment = $itinerary['flights'][0];

        return new self(
            identifier: str_replace(' ', '', $segment['flight_number']),
            airline: $segment['airline'],
            from: $segment['departure_airport']['name'],
            fromIata: $segment['departure_airport']['id'],
            to: $segment['arrival_airport']['name'],
            toIata: $segment['arrival_airport']['id'],
            departureAt: $segment['departure_airport']['time'],
            arrivalAt: $segment['arrival_airport']['time'],
            price: isset($itinerary['price']) ? (int) round($itinerary['price'] * 100) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier,
            'airline' => $this->airline,
            'from' => $this->from,
            'from_iata' => $this->fromIata,
            'to' => $this->to,
            'to_iata' => $this->toIata,
            'departure_at' => $this->departureAt,
            'arrival_at' => $this->arrivalAt,
            'price' => $this->price,
        ];
    }
}
