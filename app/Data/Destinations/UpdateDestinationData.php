<?php

namespace App\Data\Destinations;

use App\Http\Requests\Destinations\UpdateDestinationRequest;

final readonly class UpdateDestinationData
{
    public function __construct(
        public ?string $city,
        public ?string $country,
        public ?int $budget,
        public ?string $arrivalDate,
        public ?string $departureDate
    ) {}

    public static function fromRequest(UpdateDestinationRequest $request): self
    {
        return new self(
            city: $request->validated('city'),
            country: $request->validated('country_code'),
            budget: $request->validated('budget'),
            arrivalDate: $request->validated('arrival_date'),
            departureDate: $request->validated('departure_date'),
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'city' => $this->city,
            'country_code' => $this->country,
            'budget' => $this->budget,
            'arrival_date' => $this->arrivalDate,
            'departure_date' => $this->departureDate,
        ], static fn ($value) => $value !== null);
    }
}
