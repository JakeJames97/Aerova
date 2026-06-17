<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_logs_in_with_valid_credentials_and_returns_a_token(): void
    {
        User::factory()->create([
            'email' => 'example@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'example@test.com',
            'password' => 'password123',
        ]);

        $response->assertOk()->assertJsonStructure(['user' => ['id', 'username', 'email'], 'token']);
    }

    #[Test]
    public function it_logs_in_with_username(): void
    {
        User::factory()->create([
            'username' => 'example',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'example',
            'password' => 'password123',
        ]);

        $response->assertOk()->assertJsonStructure(['user' => ['id', 'username', 'email'], 'token']);
    }

    #[Test]
    public function it_rejects_an_incorrect_password_with_a_422(): void
    {
        User::factory()->create([
            'email' => 'example@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'example@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_requires_an_email_and_a_password(): void
    {
        $this->postJson('/api/login', [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['login', 'password']);
    }
}
