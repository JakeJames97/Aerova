<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SearchAirportsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_finds_an_airport_by_name(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/airports?search=edinburgh')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    ['iata', 'name'],
                ],
            ])
            ->assertJsonPath('data.0.iata', 'EDI')
            ->assertJsonPath('data.0.name', 'Edinburgh Airport');
    }

    #[Test]
    public function it_finds_an_airport_by_iata_code(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/airports?search=BHX')
            ->assertOk()
            ->assertJsonPath('data.0.iata', 'BHX');
    }

    #[Test]
    public function it_returns_nothing_for_an_unknown_airport(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/airports?search=zzzzzzzz')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_requires_a_search_term(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/airports')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('search');
    }

    #[Test]
    public function it_limits_the_number_of_results(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/airports?search=international')
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }
}
