<?php

namespace App\Interfaces\Currency;

use Illuminate\Support\Facades\Http;

class ExchangeRates implements CurrencyProviderInterface
{
    private string $exception;
    public function __construct()
    {
        $this->exception = 'Failed to convert currencies. Please try again later!';
    }

    /**
     * @throws \Exception
     */
    public function call()
    {
        try {
            $response = Http::get(config('exchange.rates_api'));

            if ($response->status() !== 200) {
                throw new \Exception($this->exception);
            }

            return $response->json()['rates'];
        } catch (\Exception $e) {
            throw new \Exception($this->exception);
        }
    }
}
