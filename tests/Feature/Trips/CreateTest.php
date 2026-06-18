<?php

namespace Tests\Feature\Trips;

use App\Enums\TripStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_trip_for_the_authenticated_user(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/trips', [
            'name' => 'Japan 2026',
            'description' => 'Two weeks',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-14',
            'status' => TripStatus::PLANNED->value,
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Japan 2026');

        $this->assertDatabaseHas('trips', [
            'name' => 'Japan 2026',
            'description' => 'Two weeks',
            'start_date' => Carbon::parse('2026-07-01'),
            'end_date' => Carbon::parse('2026-07-14'),
            'user_id' => $user->id,
            'status' => TripStatus::PLANNED->value,
        ]);
    }

    #[Test]
    public function it_validates_required_fields(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/trips', [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'start_date', 'end_date']);
    }

    #[Test]
    public function it_rejects_an_end_date_before_the_start_date(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/trips', [
            'name' => 'Bad dates',
            'start_date' => '2026-07-14',
            'end_date' => '2026-07-01',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('end_date');
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $this->postJson('/api/trips', [])->assertUnauthorized();
    }
}
