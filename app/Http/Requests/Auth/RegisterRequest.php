<?php

namespace App\Http\Requests\Auth;

use App\Data\Auth\RegisterData;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(8)->uncompromised()],
        ];
    }

    public function toDto(): RegisterData
    {
        return RegisterData::fromRequest($this);
    }
}
