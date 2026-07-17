<?php

namespace Tests\Feature\Transports;

use App\Enums\TransportType;
use App\Models\Destination;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_requires_authentication(): void
    {
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for(User::factory())))
            ->create();

        $this->putJson("/api/transports/{$transport->id}", $this->payload())
            ->assertUnauthorized();
    }

    #[Test]
    public function it_updates_a_transport(): void
    {
        $user = User::factory()->create();
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create(['price' => 8900, 'identifier' => 'BA1440']);

        Sanctum::actingAs($user);

        $this->putJson("/api/transports/{$transport->id}", $this->payload([
            'identifier' => 'BA1442',
            'price' => 12400,
        ]))
            ->assertOk()
            ->assertJsonPath('data.identifier', 'BA1442')
            ->assertJsonPath('data.price', 12400);

        $this->assertDatabaseHas('transports', [
            'id' => $transport->id,
            'identifier' => 'BA1442',
            'price' => 1240000,
        ]);
    }

    #[Test]
    public function it_can_change_the_type_and_clear_flight_fields(): void
    {
        $user = User::factory()->create();
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create();

        Sanctum::actingAs($user);

        $this->putJson("/api/transports/{$transport->id}", $this->payload([
            'type' => TransportType::TRAIN->value,
            'from' => 'Tokyo Station',
            'to' => 'Kyoto Station',
            'identifier' => 'Nozomi 21',
            'airline' => null,
            'from_iata' => null,
            'to_iata' => null,
        ]))->assertOk();

        $this->assertDatabaseHas('transports', [
            'id' => $transport->id,
            'type' => TransportType::TRAIN->value,
            'airline' => null,
            'from_iata' => null,
            'to_iata' => null,
        ]);
    }

    #[Test]
    public function it_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create();

        Sanctum::actingAs($user);

        $this->putJson("/api/transports/{$transport->id}", [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['type', 'from', 'to', 'departure_at', 'arrival_at', 'price']);
    }

    #[Test]
    public function it_rejects_an_arrival_before_the_departure(): void
    {
        $user = User::factory()->create();
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create();

        Sanctum::actingAs($user);

        $this->putJson("/api/transports/{$transport->id}", $this->payload([
            'departure_at' => '2026-10-03 10:00:00',
            'arrival_at' => '2026-10-03 09:00:00',
        ]))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('arrival_at');
    }

    #[Test]
    public function it_does_not_allow_updating_another_users_transport(): void
    {
        $user = User::factory()->create();
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for(User::factory())))
            ->create(['identifier' => 'BA1440']);

        Sanctum::actingAs($user);

        $this->putJson("/api/transports/{$transport->id}", $this->payload([
            'identifier' => 'HACKED',
        ]))->assertForbidden();

        $this->assertDatabaseHas('transports', [
            'id' => $transport->id,
            'identifier' => 'BA1440',
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'type' => TransportType::FLIGHT->value,
            'from' => 'Edinburgh Airport',
            'to' => 'Heathrow Airport',
            'identifier' => 'BA1440',
            'departure_at' => '2026-10-03 06:20:00',
            'arrival_at' => '2026-10-03 07:50:00',
            'price' => 8900,
            'airline' => 'British Airways',
            'from_iata' => 'EDI',
            'to_iata' => 'LHR',
        ], $overrides);
    }
}
