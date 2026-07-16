<?php

namespace App\Http\Requests\Destinations;

use App\Data\Destinations\CreateDestinationData;
use App\Http\Requests\BaseFormRequest;

class CreateDestinationRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'max:2'],
            'budget' => ['required', 'integer'],
            'arrival_date' => ['required', 'date'],
            'departure_date' => ['required', 'date', 'after_or_equal:arrival_date'],
        ];
    }

    public function toDto(): CreateDestinationData
    {
        return CreateDestinationData::fromRequest($this);
    }
}
