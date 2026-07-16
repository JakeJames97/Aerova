<?php

namespace App\Http\Requests\Trips;

use App\Data\Trips\UpdateTripData;
use App\Enums\TripStatus;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTripRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'description' => ['sometimes', 'string'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', new Enum(TripStatus::class)],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }

    public function toDto(): UpdateTripData
    {
        return UpdateTripData::fromRequest($this);
    }
}
