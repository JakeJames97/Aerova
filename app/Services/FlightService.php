<?php

namespace App\Services;

use App\Data\Transports\FlightData;
use App\Exceptions\FlightException;
use App\Http\Integrations\SerpApi\Requests\GetFlights;
use App\Http\Integrations\SerpApi\SerpApiConnector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class FlightService
{
    public function __construct(
        protected SerpApiConnector $connector
    ) {}

    /**
     * @return FlightData[]
     */
    public function lookup(string $fromIata, string $toIata, string $date): array
    {
        try {
            $itineraries = $this->getItineraries($fromIata, $toIata, $date);
        } catch (Throwable $throwable) {
            Log::error('An exception was thrown when looking up flights', [
                'from_iata' => $fromIata,
                'to_iata' => $toIata,
                'date' => $date,
                'exception' => $throwable,
            ]);

            return [];
        }

        return collect($itineraries)
            ->filter(fn (array $itinerary) => count($itinerary['flights'] ?? []) === 1)
            ->map(fn (array $itinerary) => FlightData::fromItinerary($itinerary))
            ->sortBy(fn (FlightData $flight) => $flight->departureAt)
            ->values()
            ->all();
    }

    protected function getItineraries(string $fromIata, string $toIata, string $date): array
    {
        return Cache::remember("flights:{$fromIata}:{$toIata}:{$date}", now()->addDay(), function () use ($fromIata, $toIata, $date) {
            $response = $this->connector->send(new GetFlights($fromIata, $toIata, $date));

            if ($response->failed()) {
                throw new FlightException('Failed to look up flights');
            }

            return array_merge(
                $response->json('best_flights') ?? [],
                $response->json('other_flights') ?? [],
            );
        });
    }
}
