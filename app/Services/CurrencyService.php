<?php

namespace App\Services;

use App\Exceptions\CurrencyException;
use App\Http\Integrations\Frankfurter\FrankfurterConnector;
use App\Http\Integrations\Frankfurter\Requests\ConvertCurrencyRequest;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    public function __construct(
        protected FrankfurterConnector $connector
    ) {}

    /**
     * @throws CurrencyException
     */
    public function convert(string $from, string $to, float $amount): int
    {
        $rate = $this->getRate($from, $to);
        $conversion = (int) round($rate * $amount, 0);

        return $conversion * 100;
    }

    protected function getRate(string $from, string $to): float
    {
        return Cache::remember("conversion_rate:{$from}:{$to}", now()->addHour(), function () use ($from, $to) {
            $response = $this->connector->send(new ConvertCurrencyRequest($from, $to));

            if ($response->failed()) {
                throw new CurrencyException('Failed to convert currency');
            }

            return (float) $response->json('rate');
        });
    }
}
