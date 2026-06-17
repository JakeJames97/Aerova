<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_revokes_the_token_on_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('spa')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertNoContent();

        $this->assertSame(0, $user->tokens()->count());
    }

    #[Test]
    public function it_rejects_logout_without_a_token(): void
    {
        $this->postJson('/api/logout')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
