<?php

namespace App\Data\Auth;

use App\Http\Requests\Auth\LoginRequest;

final readonly class LoginData
{
    public function __construct(public string $login, public string $password) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            login: $request->validated('login'),
            password: $request->validated('password'),
        );
    }
}
