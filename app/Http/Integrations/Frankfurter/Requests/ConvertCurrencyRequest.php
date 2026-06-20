<?php

namespace App\Http\Integrations\Frankfurter\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ConvertCurrencyRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $from,
        protected string $to,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/rate/{$this->from}/{$this->to}";
    }
}
