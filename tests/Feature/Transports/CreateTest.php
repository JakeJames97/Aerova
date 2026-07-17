<?php

namespace Tests\Feature\Transports;

use App\Enums\TransportType;
use App\Models\Destination;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_requires_authentication(): void
    {
        $destination = Destination::factory()->for(Trip::factory()->for(User::factory()))->create();

        $this->postJson("/api/destinations/{$destination->id}/transports", [])
            ->assertUnauthorized();
    }

    #[Test]
    public function it_creates_a_flight(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => TransportType::FLIGHT->value,
            'from' => 'Edinburgh Airport',
            'to' => 'London Heathrow',
            'identifier' => 'BA1234',
            'departure_at' => '2026-08-03 09:15:00',
            'arrival_at' => '2026-08-03 10:45:00',
            'price' => 320,
            'airline' => 'British Airways',
            'from_iata' => 'EDI',
            'to_iata' => 'LHR',
        ])
            ->assertCreated()
            ->assertJsonPath('data.price', 320)
            ->assertJsonPath('data.price_formatted', '£320.00')
            ->assertJsonPath('data.identifier', 'BA1234')
            ->assertJsonPath('data.from_iata', 'EDI')
            ->assertJsonPath('data.airline', 'British Airways');

        $this->assertDatabaseHas('transports', [
            'destination_id' => $destination->id,
            'type' => TransportType::FLIGHT->value,
            'identifier' => 'BA1234',
            'price' => 32000,
            'airline' => 'British Airways',
            'from_iata' => 'EDI',
            'to_iata' => 'LHR',
        ]);
    }

    #[Test]
    public function it_creates_a_train_without_flight_fields(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => TransportType::TRAIN->value,
            'from' => 'Tokyo Station',
            'to' => 'Kyoto Station',
            'identifier' => 'Nozomi 21',
            'price' => 9000,
            'departure_at' => '2026-08-03 09:15:00',
            'arrival_at' => '2026-08-03 10:45:00',
        ])->assertCreated();

        $this->assertDatabaseHas('transports', [
            'destination_id' => $destination->id,
            'type' => TransportType::TRAIN->value,
            'identifier' => 'Nozomi 21',
            'airline' => null,
            'from_iata' => null,
            'to_iata' => null,
        ]);
    }

    #[Test]
    public function it_allows_multiple_transports_for_one_destination(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => TransportType::FLIGHT->value,
            'from' => 'Edinburgh Airport',
            'to' => 'London Heathrow',
            'identifier' => 'BA1234',
            'departure_at' => '2026-08-03 09:15:00',
            'arrival_at' => '2026-08-03 10:45:00',
            'price' => 32000,
        ])->assertCreated();

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => TransportType::TRAIN->value,
            'from' => 'Narita Airport',
            'to' => 'London Heathrow',
            'identifier' => 'Skyliner',
            'departure_at' => '2026-08-03 09:15:00',
            'arrival_at' => '2026-08-03 10:45:00',
            'price' => 32000,
        ])->assertCreated();

        $this->assertDatabaseCount('transports', 2);
        $this->assertSame(2, $destination->transports()->count());
    }

    #[Test]
    public function it_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['type', 'from', 'to', 'price', 'departure_at', 'arrival_at']);
    }

    #[Test]
    public function it_rejects_an_invalid_type(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => 'teleport',
            'from' => 'Falkirk',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('type');
    }

    #[Test]
    public function it_rejects_an_arrival_before_the_departure(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => TransportType::FLIGHT->value,
            'from' => 'Edinburgh Airport',
            'to' => 'London Heathrow',
            'price' => 32000,
            'departure_at' => '2026-08-03 10:00:00',
            'arrival_at' => '2026-08-03 09:00:00',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('arrival_at');
    }

    #[Test]
    public function it_does_not_allow_adding_transport_to_another_users_destination(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for(User::factory()))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/transports", [
            'type' => TransportType::FLIGHT->value,
            'from' => 'Edinburgh Airport',
            'to' => 'London Heathrow',
            'departure_at' => '2026-08-03 09:15:00',
            'arrival_at' => '2026-08-03 10:45:00',
            'price' => 32000,
        ])->assertForbidden();

        $this->assertDatabaseCount('transports', 0);
    }
}
