<?php

namespace App\Http\Integrations\SerpApi;

use Saloon\Http\Connector;

class SerpApiConnector extends Connector
{
    public function __construct(private readonly string $apiKey) {}

    public function resolveBaseUrl(): string
    {
        return 'https://serpapi.com';
    }

    protected function defaultQuery(): array
    {
        return [
            'api_key' => $this->apiKey,
        ];
    }
}
