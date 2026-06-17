<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_registers_a_new_user_and_returns_a_token(): void
    {
        $user = User::factory()->raw();
        $response = $this->postJson('/api/register', $user);

        $response->assertCreated()
            ->assertJsonFragment([
                'username' => $user['username'],
            ])
            ->assertJsonFragment([
                'email' => $user['email'],
            ]);

        $this->assertTrue(User::where('email', $user['email'])->exists());
    }

    #[Test]
    public function it_rejects_an_email_that_is_already_taken(): void
    {
        User::factory()->create(['email' => 'example@test.com']);

        $user = User::factory()->raw([
            'email' => 'example@test.com',
        ]);

        $response = $this->postJson('/api/register', $user);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors('email');
    }
}
