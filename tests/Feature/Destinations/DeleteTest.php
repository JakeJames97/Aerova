<?php

namespace Tests\Feature\Destinations;

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
    public function it_deletes_an_owned_destination(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $destination = Destination::factory()->for($trip)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/destinations/{$destination->id}")->assertNoContent();

        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
    }

    #[Test]
    public function it_cascades_to_tasks(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $destination = Destination::factory()->for($trip)->create();
        $task = Task::factory()->for($destination)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/destinations/{$destination->id}")->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_forbids_deleting_another_users_destination(): void
    {
        $trip = Trip::factory()->for(User::factory()->create())->create();
        $destination = Destination::factory()->for($trip)->create();

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/destinations/{$destination->id}")->assertForbidden();

        $this->assertDatabaseHas('destinations', ['id' => $destination->id]);
    }
}
