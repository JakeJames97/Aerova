<?php

namespace Tests\Feature\Transports;

use App\Http\Integrations\SerpApi\Requests\GetFlights;
use App\Models\Destination;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LookupFlightsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_requires_authentication(): void
    {
        $destination = Destination::factory()->for(Trip::factory()->for(User::factory()))->create();

        $this->getJson($this->url($destination))->assertUnauthorized();
    }

    #[Test]
    public function it_returns_flights_for_a_route(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::fixture('serpapi/lookup'),
        ]);

        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->getJson($this->url($destination))
            ->assertOk()
            ->assertJsonPath('data.0.identifier', 'BA1465')
            ->assertJsonPath('data.0.airline', 'British Airways')
            ->assertJsonPath('data.0.from_iata', 'EDI')
            ->assertJsonPath('data.0.to_iata', 'LHR')
            ->assertJsonPath('data.0.price', 11600);
    }

    #[Test]
    public function it_returns_an_empty_list_when_the_api_fails(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::make([], 500),
        ]);

        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->getJson($this->url($destination))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_returns_an_empty_list_when_there_are_no_flights(): void
    {
        Saloon::fake([
            GetFlights::class => MockResponse::make(['best_flights' => [], 'other_flights' => []], 200),
        ]);

        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->getJson($this->url($destination))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_validates_the_query(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/destinations/{$destination->id}/flights")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['from_iata', 'to_iata', 'date']);
    }

    #[Test]
    public function it_does_not_allow_looking_up_flights_for_another_users_destination(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for(User::factory()))->create();

        Sanctum::actingAs($user);

        $this->getJson($this->url($destination))->assertForbidden();
    }

    private function url(Destination $destination, array $params = []): string
    {
        $query = http_build_query(array_merge([
            'from_iata' => 'EDI',
            'to_iata' => 'LHR',
            'date' => '2026-08-03',
        ], $params));

        return "/api/destinations/{$destination->id}/flights?{$query}";
    }
}
