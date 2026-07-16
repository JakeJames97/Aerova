<?php

namespace App\Http\Requests\Destinations;

use App\Data\Destinations\UpdateDestinationData;
use App\Http\Requests\BaseFormRequest;

class UpdateDestinationRequest extends BaseFormRequest
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

    public function toDto(): UpdateDestinationData
    {
        return UpdateDestinationData::fromRequest($this);
    }
}
