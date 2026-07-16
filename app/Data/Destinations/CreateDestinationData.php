<?php

namespace App\Data\Destinations;

use App\Http\Requests\Destinations\CreateDestinationRequest;

final readonly class CreateDestinationData
{
    public function __construct(
        public string $city,
        public string $country,
        public int $budget,
        public string $arrivalDate,
        public string $departureDate
    ) {}

    public static function fromRequest(CreateDestinationRequest $request): self
    {
        return new self(
            city: $request->validated('city'),
            country: $request->validated('country_code'),
            budget: $request->validated('budget'),
            arrivalDate: $request->validated('arrival_date'),
            departureDate: $request->validated('departure_date'),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            city: $data['city'],
            country: $data['country_code'],
            budget: $data['budget'],
            arrivalDate: $data['arrival_date'],
            departureDate: $data['departure_date'],
        );
    }

    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'country_code' => $this->country,
            'budget' => $this->budget,
            'arrival_date' => $this->arrivalDate,
            'departure_date' => $this->departureDate,
        ];
    }
}
