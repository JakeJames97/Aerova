<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticatedUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_the_authenticated_user(): void
    {
        $user = User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'username' => 'test',
                    'email' => 'test@example.com',
                ],
            ]);
    }

    #[Test]
    public function it_requires_authentication(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }
}
