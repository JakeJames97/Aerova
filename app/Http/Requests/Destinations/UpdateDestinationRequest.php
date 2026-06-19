<?php

namespace App\Http\Requests\Destinations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'arrival_date' => ['sometimes', 'date'],
            'departure_date' => ['sometimes', 'date', 'after_or_equal:arrival_date'],
        ];
    }
}
