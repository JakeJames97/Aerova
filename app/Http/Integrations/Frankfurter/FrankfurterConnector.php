<?php

namespace App\Http\Integrations\Frankfurter;

use Saloon\Http\Connector;

class FrankfurterConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://api.frankfurter.dev/v2';
    }
}
