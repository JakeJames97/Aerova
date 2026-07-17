<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\AirportResource;
use App\Models\Airport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AirportResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_expected_shape(): void
    {
        $airport = Airport::factory()->create();

        $array = new AirportResource($airport)->toArray(Request::create('/'));

        $this->assertSame($airport->name, $array['name']);
        $this->assertSame($airport->iata, $array['iata']);
    }
}
