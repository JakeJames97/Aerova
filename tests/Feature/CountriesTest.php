<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_list_of_countries(): void
    {
        Country::factory()->create(['name' => 'France', 'code' => 'FR']);
        Country::factory()->create(['name' => 'Japan', 'code' => 'JP']);

        $response = $this->getJson('/countries');

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'name' => 'France',
                    'code' => 'FR',
                ],
                [
                    'name' => 'Japan',
                    'code' => 'JP',
                ],
            ],
        ]);
    }
}
