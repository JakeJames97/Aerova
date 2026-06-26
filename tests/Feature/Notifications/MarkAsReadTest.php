<?php

namespace Tests\Feature\Notifications;

use App\Models\Trip;
use App\Models\User;
use App\Notifications\TripLiked;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MarkAsReadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_marks_a_notification_as_read(): void
    {
        $user = User::factory()->create();
        $liker = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $user->notify(new TripLiked($trip, $liker));

        Sanctum::actingAs($user);

        $notification = $user->notifications()->first();
        $this->assertNull($notification->read_at);

        $this->patchJson("/api/notifications/{$notification->id}/read")
            ->assertOk();

        $this->assertNotNull($user->fresh()->notifications()->first()->read_at);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $user = User::factory()->create();
        $liker = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        $user->notify(new TripLiked($trip, $liker));

        $notification = $user->notifications()->first();

        $this->patchJson("/api/notifications/{$notification->id}/read")->assertUnauthorized();
    }
}
