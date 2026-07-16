<?php

namespace Tests\Feature\Trips;

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
    public function it_updates_an_owned_trip(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create(['name' => 'Old name']);

        Sanctum::actingAs($user);

        $this->putJson("/api/trips/{$trip->id}", [
            'name' => 'New name',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'New name');

        $this->assertDatabaseHas('trips', [
            'id' => $trip->id,
            'name' => 'New name',
            'start_date' => Carbon::parse('2026-07-01'),
            'end_date' => Carbon::parse('2026-07-14'),
        ]);
    }

    #[Test]
    public function it_forbids_updating_another_users_trip(): void
    {
        $trip = Trip::factory()->for(User::factory()->create())->create(['name' => 'Original']);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/trips/{$trip->id}", [
            'name' => 'Hijacked',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
        ])->assertForbidden();

        $this->assertDatabaseHas('trips', [
            'id' => $trip->id,
            'name' => 'Original',
        ]);
    }

    #[Test]
    public function it_does_not_change_fields_that_were_not_sent(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create([
            'name' => 'Original',
            'description' => 'Original description',
            'is_public' => true,
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/trips/{$trip->id}", ['name' => 'Updated'])
            ->assertOk();

        $this->assertDatabaseHas('trips', [
            'id' => $trip->id,
            'name' => 'Updated',
            'description' => 'Original description',
            'is_public' => true,
        ]);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $trip = Trip::factory()->create();

        $this->putJson("/api/trips/{$trip->id}", [])->assertUnauthorized();
    }
}
