<?php

namespace Tests\Feature\Transports;

use App\Models\Destination;
use App\Models\Transport;
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
    public function it_deletes_an_owned_transport(): void
    {
        $user = User::factory()->create();
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for($user)))
            ->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/transports/{$transport->id}")->assertNoContent();

        $this->assertDatabaseMissing('transports', ['id' => $transport->id]);
    }

    #[Test]
    public function it_forbids_deleting_another_users_transport(): void
    {
        $transport = Transport::factory()
            ->for(Destination::factory()->for(Trip::factory()->for(User::factory())))
            ->create();

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/transports/{$transport->id}")->assertForbidden();

        $this->assertDatabaseHas('transports', ['id' => $transport->id]);
    }
}
