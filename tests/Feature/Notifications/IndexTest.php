<?php

namespace Tests\Feature\Notifications;

use App\Models\Trip;
use App\Models\User;
use App\Notifications\TripLiked;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_the_authenticated_users_notifications(): void
    {
        $user = User::factory()->create();
        $liker = User::factory()->create();
        $trip = Trip::factory()->for($user)->create();

        for ($i = 0; $i < 3; $i++) {
            $user->notify(new TripLiked($trip, $liker));
        }

        Sanctum::actingAs($user);

        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function it_requires_authentication(): void
    {
        $this->patchJson('/api/notifications')->assertUnauthorized();
    }
}
