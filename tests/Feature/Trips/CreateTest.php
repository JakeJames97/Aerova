<?php

namespace Tests\Feature\Trips;

use App\Enums\TripStatus;
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
    public function it_creates_a_trip_for_the_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/trips', [
            'name' => 'Japan 2026',
            'description' => 'Two weeks',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
            'status' => TripStatus::PLANNED->value,
            'is_public' => true,
            'destinations' => [],
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Japan 2026');

        $this->assertDatabaseHas('trips', [
            'name' => 'Japan 2026',
            'description' => 'Two weeks',
            'start_date' => Carbon::parse('2026-07-01'),
            'end_date' => Carbon::parse('2026-07-14'),
            'user_id' => $user->id,
            'status' => TripStatus::PLANNED->value,
            'is_public' => true,
        ]);
    }

    #[Test]
    public function it_creates_a_trip_with_destinations(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/trips', [
            'name' => 'Japan 2026',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
            'status' => TripStatus::PLANNED->value,
            'is_public' => true,
            'destinations' => [
                [
                    'city' => 'Tokyo',
                    'country_code' => 'JP',
                    'budget' => 120000,
                    'arrival_date' => '2026-07-01',
                    'departure_date' => '2026-07-07',
                ],
                [
                    'city' => 'Kyoto',
                    'country_code' => 'JP',
                    'budget' => 80000,
                    'arrival_date' => '2026-07-07',
                    'departure_date' => '2026-07-14',
                ],
            ],
        ]);

        $response->assertCreated();

        $tripId = $response->json('data.id');

        $this->assertDatabaseHas('destinations', [
            'trip_id' => $tripId,
            'city' => 'Tokyo',
            'country_code' => 'JP',
        ]);
        $this->assertDatabaseHas('destinations', [
            'trip_id' => $tripId,
            'city' => 'Kyoto',
            'country_code' => 'JP',
        ]);

        $this->assertDatabaseCount('destinations', 2);
    }

    #[Test]
    public function it_rolls_back_the_trip_if_a_destination_is_invalid(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/trips', [
            'name' => 'Should not persist',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
            'status' => TripStatus::PLANNED->value,
            'is_public' => true,
            'destinations' => [
                [
                    'city' => 'Tokyo',
                    'country_code' => 'JP',
                    'budget' => 120000,
                    'arrival_date' => '2026-07-01',
                    'departure_date' => '2026-07-07',
                ],
                [
                    'city' => '',
                ],
            ],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('trips', ['name' => 'Should not persist']);
        $this->assertDatabaseCount('destinations', 0);
    }

    #[Test]
    public function it_validates_destination_fields(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/trips', [
            'name' => 'Japan 2026',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
            'status' => TripStatus::PLANNED->value,
            'is_public' => true,
            'destinations' => [
                ['city' => ''],
            ],
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'destinations.0.city',
                'destinations.0.country_code',
                'destinations.0.arrival_date',
                'destinations.0.departure_date',
            ]);
    }

    #[Test]
    public function it_validates_required_trip_fields(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/trips', [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'start_date', 'end_date']);
    }

    #[Test]
    public function it_rejects_an_end_date_before_the_start_date(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/trips', [
            'name' => 'Bad dates',
            'start_date' => '2026-07-14',
            'end_date' => '2026-07-01',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('end_date');
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $this->postJson('/api/trips', [])->assertUnauthorized();
    }
}
