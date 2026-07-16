<?php

namespace App\Http\Requests\Trips;

use App\Data\Trips\GetTripsData;
use App\Enums\TripStatus;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rules\Enum;

class GetTripsRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer'],
            'country' => ['sometimes', 'string'],
            'search' => ['sometimes', 'string'],
            'status' => ['sometimes', new Enum(TripStatus::class)],
        ];
    }

    public function toDto(): GetTripsData
    {
        return GetTripsData::fromRequest($this);
    }
}
