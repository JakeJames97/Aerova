<?php

namespace App\Http\Integrations\SerpApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetFlights extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $fromIata,
        private readonly string $toIata,
        private readonly string $date,
        private readonly string $currency = 'GBP',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/search';
    }

    protected function defaultQuery(): array
    {
        return [
            'engine' => 'google_flights',
            'departure_id' => $this->fromIata,
            'arrival_id' => $this->toIata,
            'outbound_date' => $this->date,
            'type' => 2,
            'currency' => $this->currency,
            'hl' => 'en',
            'gl' => 'uk',
        ];
    }
}
