<?php

namespace App\Data\Trips;

use App\Enums\TripStatus;
use App\Http\Requests\Trips\UpdateTripRequest;

final readonly class UpdateTripData
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?TripStatus $status = null,
        public ?bool $isPublic = null,
    ) {}

    public static function fromRequest(UpdateTripRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
            startDate: $request->validated('start_date'),
            endDate: $request->validated('end_date'),
            status: $request->has('status')
                ? TripStatus::from($request->validated('status'))
                : null,
            isPublic: $request->has('is_public')
                ? $request->boolean('is_public')
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => $this->status,
            'is_public' => $this->isPublic,
        ], static fn ($value) => $value !== null);
    }
}
