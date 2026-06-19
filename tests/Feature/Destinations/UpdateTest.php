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
            'name' => 'old name',
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/destinations/{$destination->id}", [
            'name' => 'New name',
            'arrival_date' => '2026-07-01',
            'departure_date' => '2026-07-14',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'New name');

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'name' => 'New name',
            'arrival_date' => Carbon::parse('2026-07-01'),
            'departure_date' => Carbon::parse('2026-07-14'),
        ]);
    }

    #[Test]
    public function it_forbids_updating_another_users_destination(): void
    {
        $trip = Trip::factory()->for(User::factory()->create())->create(['name' => 'Original']);
        $destination = Destination::factory()->for($trip)->create([
            'name' => 'original',
        ]);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/destinations/{$destination->id}", [
            'name' => 'Hijacked',
            'arrival_date' => '2026-07-01',
            'departure_date' => '2026-07-14',
        ])->assertForbidden();

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'name' => 'original',
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
