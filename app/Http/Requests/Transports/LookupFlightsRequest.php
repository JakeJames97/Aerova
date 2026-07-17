<?php

namespace App\Http\Requests\Transports;

use App\Data\Transports\LookupFlightsData;
use App\Http\Requests\BaseFormRequest;

class LookupFlightsRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_iata' => ['required', 'string', 'size:3'],
            'to_iata' => ['required', 'string', 'size:3'],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }

    public function toDto(): LookupFlightsData
    {
        return LookupFlightsData::fromRequest($this);
    }
}
