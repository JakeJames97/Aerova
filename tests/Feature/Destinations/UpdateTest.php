<?php

namespace Tests\Feature\Destinations;

use App\Models\Destination;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_an_owned_destination(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $destination = Destination::factory()->for($trip)->create([
            'city' => 'Osaka',
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/destinations/{$destination->id}", [
            'city' => 'Tokyo',
            'country_code' => 'JP',
            'budget' => 10000,
            'arrival_date' => '2026-07-01',
            'departure_date' => '2026-07-14',
        ])
            ->assertOk()
            ->assertJsonPath('data.city', 'Tokyo');

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'city' => 'Tokyo',
            'country_code' => 'JP',
            'budget' => 10000,
            'arrival_date' => Carbon::parse('2026-07-01'),
            'departure_date' => Carbon::parse('2026-07-14'),
        ]);
    }

    #[Test]
    public function it_forbids_updating_another_users_destination(): void
    {
        $trip = Trip::factory()->for(User::factory()->create())->create();
        $destination = Destination::factory()->for($trip)->create([
            'city' => 'Tokyo',
        ]);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/destinations/{$destination->id}", [
            'city' => 'Hijacked',
            'country_code' => 'JP',
            'budget' => 10000,
            'arrival_date' => '2026-07-01',
            'departure_date' => '2026-07-14',
        ])->assertForbidden();

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'city' => 'Tokyo',
        ]);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $trip = Trip::factory()->create();
        $destination = Destination::factory()->for($trip)->create();

        $this->putJson("/api/destinations/{$destination->id}", [])->assertUnauthorized();
    }
}
