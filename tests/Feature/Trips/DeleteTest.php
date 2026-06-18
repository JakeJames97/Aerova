<?php

namespace Tests\Feature\Trips;

use App\Models\Destination;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_an_owned_trip(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/trips/{$trip->id}")->assertNoContent();

        $this->assertDatabaseMissing('trips', ['id' => $trip->id]);
    }

    #[Test]
    public function it_cascades_to_destinations(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $destination = Destination::factory()->for($trip)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/trips/{$trip->id}")->assertNoContent();

        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
    }

    #[Test]
    public function it_forbids_deleting_another_users_trip(): void
    {
        $trip = Trip::factory()->for(User::factory()->create())->create();

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/trips/{$trip->id}")->assertForbidden();

        $this->assertDatabaseHas('trips', ['id' => $trip->id]);
    }
}
