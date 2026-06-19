<?php

namespace Tests\Feature\Tasks;

use App\Models\Destination;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_task_on_an_owned_destination(): void
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();
        $destination = Destination::factory()->for($trip)->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/tasks", [
            'title' => 'Book hotel',
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Book hotel')
            ->assertJsonPath('data.is_completed', false);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Book hotel',
            'destination_id' => $destination->id,
            'is_completed' => false,
        ]);
    }

    #[Test]
    public function it_fails_validation(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->for(Trip::factory()->for($user))->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/destinations/{$destination->id}/tasks", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('title');
    }

    #[Test]
    public function it_forbids_adding_a_task_to_another_users_destination(): void
    {
        $destination = Destination::factory()->for(Trip::factory()->for(User::factory()))->create();

        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/destinations/{$destination->id}/tasks", [
            'title' => 'task',
        ])->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['title' => 'task']);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $destination = Destination::factory()
            ->for(Trip::factory()->for(User::factory()))
            ->create();

        $this->postJson("/api/destinations/{$destination->id}/tasks", ['title' => 'task'])
            ->assertUnauthorized();
    }
}
