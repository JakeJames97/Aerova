<?php

namespace Tests\Unit\Resources;

use App\Data\Transports\FlightData;
use App\Http\Resources\FlightResource;
use Illuminate\Http\Request;
use Tests\TestCase;

class FlightResourceTest extends TestCase
{
    public function test_it_returns_the_expected_shape(): void
    {
        $flight = new FlightData(
            identifier: 'BA1465',
            airline: 'British Airways',
            from: 'Edinburgh Airport',
            fromIata: 'EDI',
            to: 'Heathrow Airport',
            toIata: 'LHR',
            departureAt: '2026-08-03 06:20',
            arrivalAt: '2026-08-03 07:55',
            price: 11600,
        );

        $array = new FlightResource($flight)->toArray(Request::create('/'));

        $this->assertSame('BA1465', $array['identifier']);
        $this->assertSame('British Airways', $array['airline']);
        $this->assertSame('Edinburgh Airport', $array['from']);
        $this->assertSame('EDI', $array['from_iata']);
        $this->assertSame('Heathrow Airport', $array['to']);
        $this->assertSame('LHR', $array['to_iata']);
        $this->assertSame('2026-08-03 06:20', $array['departure_at']);
        $this->assertSame('2026-08-03 07:55', $array['arrival_at']);
        $this->assertSame(11600, $array['price']);
    }

    public function test_it_returns_a_null_price(): void
    {
        $flight = new FlightData(
            identifier: 'BA1465',
            airline: 'British Airways',
            from: 'Edinburgh Airport',
            fromIata: 'EDI',
            to: 'Heathrow Airport',
            toIata: 'LHR',
            departureAt: '2026-08-03 06:20',
            arrivalAt: '2026-08-03 07:55',
        );

        $array = new FlightResource($flight)->toArray(Request::create('/'));

        $this->assertNull($array['price']);
    }
}
