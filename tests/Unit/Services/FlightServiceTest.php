<?php

namespace Tests\Unit\Services;

use App\Data\Transports\FlightData;
use App\Http\Integrations\SerpApi\Requests\GetFlights;
use App\Http\Integrations\SerpApi\SerpApiConnector;
use App\Services\FlightService;
use Illuminate\Support\Facades\Cache;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;
use Tests\TestCase;

class FlightServiceTest extends TestCase
{
    private FlightService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->service = new FlightService(new SerpApiConnector('test-key'));
    }

    public function test_lookup_returns_flight_data(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::fixture('serpapi/lookup'),
        ]);

        $flights = $this->service->lookup('EDI', 'LHR', '2026-08-03');

        $this->assertContainsOnlyInstancesOf(FlightData::class, $flights);

        $first = $flights[0];

        $this->assertSame('BA1465', $first->identifier);
        $this->assertSame('British Airways', $first->airline);
        $this->assertSame('Edinburgh Airport', $first->from);
        $this->assertSame('EDI', $first->fromIata);
        $this->assertSame('Heathrow Airport', $first->to);
        $this->assertSame('LHR', $first->toIata);
        $this->assertSame('2026-08-03 06:20', $first->departureAt);
        $this->assertSame('2026-08-03 07:55', $first->arrivalAt);
        $this->assertSame(11600, $first->price);
    }

    public function test_lookup_excludes_flights_with_connections(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::fixture('serpapi/lookup'),
        ]);

        $flights = $this->service->lookup('EDI', 'LHR', '2026-08-03');

        $this->assertCount(11, $flights);

        $identifiers = array_map(fn (FlightData $flight) => $flight->identifier, $flights);

        $this->assertNotContains('EI3553', $identifiers);
        $this->assertNotContains('KL932', $identifiers);
    }

    public function test_lookup_merges_best_and_other_flights(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::fixture('serpapi/lookup'),
        ]);

        $identifiers = array_map(
            fn (FlightData $flight) => $flight->identifier,
            $this->service->lookup('EDI', 'LHR', '2026-08-03'),
        );

        $this->assertContains('BA1457', $identifiers);
        $this->assertContains('BA1465', $identifiers);
    }

    public function test_lookup_returns_an_empty_array_on_failure(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::make(body: [], status: 500),
        ]);

        $this->assertSame([], $this->service->lookup('EDI', 'LHR', '2026-08-03'));
    }

    public function test_lookup_returns_an_empty_array_when_there_are_no_flights(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::make(body: [
                'best_flights' => [],
                'other_flights' => [],
            ]),
        ]);

        $this->assertSame([], $this->service->lookup('EDI', 'LHR', '2026-08-03'));
    }

    public function test_itineraries_are_cached(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::fixture('serpapi/lookup'),
        ]);

        $this->service->lookup('EDI', 'LHR', '2026-08-03');
        $this->service->lookup('EDI', 'LHR', '2026-08-03');

        Saloon::assertSentCount(1);
    }

    public function test_a_different_route_is_not_cached(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::fixture('serpapi/lookup'),
        ]);

        $this->service->lookup('EDI', 'LHR', '2026-08-03');
        $this->service->lookup('EDI', 'LGW', '2026-08-03');

        Saloon::assertSentCount(2);
    }

    public function test_a_failure_is_not_cached(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::make(body: [], status: 500),
        ]);

        $this->service->lookup('EDI', 'LHR', '2026-08-03');
        $this->service->lookup('EDI', 'LHR', '2026-08-03');

        Saloon::assertSentCount(2);
    }
}
