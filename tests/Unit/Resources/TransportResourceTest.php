<?php

namespace Tests\Unit\Resources;

use App\Enums\TransportType;
use App\Http\Resources\TransportResource;
use App\Models\Destination;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TransportResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_expected_shape(): void
    {
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for(User::factory())))
            ->create([
                'type' => TransportType::FLIGHT,
                'from' => 'Edinburgh Airport',
                'to' => 'London Heathrow',
                'identifier' => 'BA1234',
                'departure_at' => '2026-08-03 09:15:00',
                'arrival_at' => '2026-08-03 10:45:00',
                'price' => 32000,
                'airline' => 'British Airways',
                'from_iata' => 'EDI',
                'to_iata' => 'LHR',
            ]);

        $array = new TransportResource($transport)->toArray(Request::create('/'));

        $this->assertSame($transport->id, $array['id']);
        $this->assertSame(TransportType::FLIGHT, $array['type']);
        $this->assertSame('Edinburgh Airport', $array['from']);
        $this->assertSame('London Heathrow', $array['to']);
        $this->assertSame('BA1234', $array['identifier']);
        $this->assertSame(32000, $array['price']);
        $this->assertSame('£320.00', $array['price_formatted']);
        $this->assertSame('British Airways', $array['airline']);
        $this->assertSame('EDI', $array['from_iata']);
        $this->assertSame('LHR', $array['to_iata']);
        $this->assertNull($array['current_price']);
        $this->assertNull($array['price_checked_at']);
    }

    public function test_it_returns_the_current_price_once_checked(): void
    {
        $transport = Transport::factory()
            ->priceChecked(48000)
            ->for(Destination::factory()->for(Trip::factory()->for(User::factory())))
            ->create(['price' => 32000]);

        $array = new TransportResource($transport)->toArray(Request::create('/'));

        $this->assertSame(32000, $array['price']);
        $this->assertSame(48000, $array['current_price']);
        $this->assertSame(
            $transport->price_checked_at->format('Y-m-d H:i:s'),
            $array['price_checked_at'],
        );
    }
}
