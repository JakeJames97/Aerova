<?php

namespace Tests\Unit\Services;

use App\Exceptions\CurrencyException;
use App\Http\Integrations\Frankfurter\FrankfurterConnector;
use App\Http\Integrations\Frankfurter\Requests\ConvertCurrencyRequest;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Cache;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    private CurrencyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->service = new CurrencyService(new FrankfurterConnector);
    }

    public function test_convert_returns_rounded_conversion(): void
    {
        Saloon::fake([
            ConvertCurrencyRequest::class => MockResponse::make(body: [
                'base' => 'GBP',
                'quote' => 'USD',
                'rate' => 1.3391,
            ]),
        ]);

        $result = $this->service->convert('GBP', 'USD', 100);

        $this->assertSame(13400, $result);
    }

    public function test_convert_throws_exception_on_failure(): void
    {
        $this->expectException(CurrencyException::class);

        Saloon::fake([
            ConvertCurrencyRequest::class => MockResponse::make(body: [], status: 500),
        ]);

        $this->service->convert('GBP', 'USD', 100);
    }

    public function test_rate_is_cached(): void
    {
        Saloon::fake([
            ConvertCurrencyRequest::class => MockResponse::make(body: [
                'base' => 'GBP', 'quote' => 'USD', 'rate' => 1.3391,
            ]),
        ]);

        $this->service->convert('GBP', 'USD', 100);
        $this->service->convert('GBP', 'USD', 250);
        Saloon::assertSentCount(1);
    }
}
