<?php

namespace App\Data\Auth;

use App\Http\Requests\Auth\RegisterRequest;

final readonly class RegisterData
{
    public function __construct(public string $username, public string $email, public string $password) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        return new self(
            username: $request->validated('username'),
            email: $request->validated('email'),
            password: $request->validated('password'),
        );
    }
}
