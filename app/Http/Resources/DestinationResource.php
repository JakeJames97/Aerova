<?php

namespace App\Http\Resources;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\Country;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $budget = $this->resource->budget;

        return [
            'id' => $this->resource->id,
            'city' => $this->resource->city,
            'country_code' => $this->resource->country_code,
            'budget' => $budget,
            'budget_formatted' => $this->resource->budget !== null
                ? Money::GBP((int) round($this->resource->budget) * 100)->format()
                : null,
            'converted_budget_formatted' => $this->getConvertedToLocalCurrencyBudget($this->resource->country, $budget),
            'arrival_date' => $this->resource->arrival_date?->toDateString(),
            'departure_date' => $this->resource->departure_date?->toDateString(),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }

    protected function getConvertedToLocalCurrencyBudget(?Country $country, ?int $budget): ?string
    {
        if (!$budget || !$country || $country->currency === 'GBP') {
            return null;
        }

        $convertedAmount = app()->make(CurrencyService::class)->convert('GBP', $country->currency, $budget);

        return new Money($convertedAmount, new Currency($country->currency));
    }
}
