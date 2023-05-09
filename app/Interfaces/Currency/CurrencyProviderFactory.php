<?php

namespace App\Interfaces\Currency;

class CurrencyProviderFactory
{
    public static function getCurrencyRateProvider(): CurrencyProviderInterface
    {
        return new ExchangeRates();
    }
}
