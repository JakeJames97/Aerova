<?php

namespace App\Data\Trips;

use App\Data\Destinations\CreateDestinationData;
use App\Enums\TripStatus;
use App\Http\Requests\Trips\CreateTripRequest;

final readonly class CreateTripData
{
    /**
     * @param CreateDestinationData[] $destinations
     */
    public function __construct(
        public string $name,
        public ?string $description,
        public string $startDate,
        public string $endDate,
        public TripStatus $status,
        public bool $isPublic,
        public array $destinations = [],
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => $this->status,
            'is_public' => $this->isPublic,
        ];
    }

    public static function fromRequest(CreateTripRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
            startDate: $request->validated('start_date'),
            endDate: $request->validated('end_date'),
            status: TripStatus::from($request->validated('status')),
            isPublic: $request->boolean('is_public'),
            destinations: array_map(
                static fn (array $destination) => CreateDestinationData::fromArray($destination),
                $request->validated('destinations', []),
            ),
        );
    }
}
