<?php

namespace Tests\Feature\Profile;

use App\Models\Destination;
use App\Models\Task;
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
    public function it_deletes_the_authenticated_user_with_associated_trips_destinations_likes_and_tasks(): void
    {
        $user = User::factory()->create();
        $user->createToken('spa');

        $otherTrip = Trip::factory()->for(User::factory())->create(['is_public' => true]);

        $user->likedTrips()->attach($otherTrip);
        $trip = Trip::factory()->for($user)->create();
        $destination = Destination::factory()->for($trip)->create();
        $task = Task::factory()->for($destination)->create();

        Sanctum::actingAs($user);

        $this->deleteJson('/api/profile')->assertNoContent();

        $this->assertDatabaseMissing('trip_likes', ['trip_id' => $trip->id]);
        $this->assertDatabaseMissing('trips', ['id' => $trip->id]);
        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $this->deleteJson('/api/profile')->assertUnauthorized();
    }
}
