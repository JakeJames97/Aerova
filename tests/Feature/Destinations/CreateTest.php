<?php

namespace Tests\Feature\Destinations;

use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_destination_for_the_associated_trip(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $tripId = $trip->id;

        Sanctum::actingAs($user);

        $this->postJson("/api/trips/{$tripId}/destinations", [
            'city' => 'Tokyo',
            'country_code' => 'JP',
            'budget' => 10000,
            'arrival_date' => '2026-07-01',
            'departure_date' => '2026-07-14',
        ])
            ->assertCreated()
            ->assertJsonPath('data.city', 'Tokyo');

        $this->assertDatabaseHas('destinations', [
            'city' => 'Tokyo',
            'country_code' => 'JP',
            'budget' => 10000,
            'trip_id' => $trip->id,
            'arrival_date' => Carbon::parse('2026-07-01'),
            'departure_date' => Carbon::parse('2026-07-14'),
        ]);
    }

    #[Test]
    public function it_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $tripId = $trip->id;
        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/trips/{$tripId}/destinations", [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['city', 'country_code', 'budget', 'arrival_date', 'departure_date']);
    }

    #[Test]
    public function it_rejects_an_departure_date_before_the_arrival_date(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $tripId = $trip->id;

        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/trips/{$tripId}/destinations", [
            'city' => 'Tokyo',
            'country_code' => 'JP',
            'budget' => 10000,
            'arrival_date' => '2026-07-14',
            'departure_date' => '2026-07-01',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('departure_date');
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $tripId = $trip->id;

        $this->postJson("/api/trips/{$tripId}/destinations", [])->assertUnauthorized();
    }

    #[Test]
    public function it_will_reject_if_the_user_does_not_own_the_trip(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $trip = Trip::factory()->create();
        $tripId = $trip->id;

        $this->postJson("/api/trips/{$tripId}/destinations", [
            'city' => 'Tokyo',
            'country_code' => 'JP',
            'budget' => 10000,
            'arrival_date' => '2026-07-01',
            'departure_date' => '2026-07-14',
        ])->assertForbidden();
    }
}
