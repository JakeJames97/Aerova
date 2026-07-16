<?php

namespace App\Http\Requests\Auth;

use App\Data\Auth\LoginData;
use App\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function toDto(): LoginData
    {
        return LoginData::fromRequest($this);
    }
}
