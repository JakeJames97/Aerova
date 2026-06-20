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
            'city' => ['sometimes', 'string', 'max:255'],
            'country_code' => ['sometimes', 'string', 'max:2'],
            'budget' => ['sometimes', 'integer'],
            'arrival_date' => ['sometimes', 'date'],
            'departure_date' => ['sometimes', 'date', 'after_or_equal:arrival_date'],
        ];
    }
}
