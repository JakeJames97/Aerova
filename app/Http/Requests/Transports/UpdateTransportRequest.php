<?php

namespace App\Http\Requests\Transports;

use App\Data\Transports\UpdateTransportData;
use App\Enums\TransportType;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateTransportRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(TransportType::class)],
            'from' => ['required', 'string', 'max:255'],
            'to' => ['required', 'string', 'max:255'],
            'identifier' => ['nullable', 'string', 'max:50'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after_or_equal:departure_at'],
            'price' => ['required', 'integer', 'min:0'],

            'airline' => ['nullable', 'string', 'max:255'],
            'from_iata' => ['nullable', 'string', 'size:3'],
            'to_iata' => ['nullable', 'string', 'size:3'],
        ];
    }

    public function toDto(): UpdateTransportData
    {
        return UpdateTransportData::fromRequest($this);
    }
}
