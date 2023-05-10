<?php

namespace App\Traits;

use App\Services\ExchangeOperations;

trait PercentageCalculatorTrait
{
    public function depositPercentageCalculator($amount): float
    {
        return self::ceilUp($amount / 100 * config('percentages.deposit'));
    }

    /**
     * @throws \Exception
     */
    public function withdrawPercentageCalculator($amount, $rates, $convertTo): ?float
    {
        $exchange = new ExchangeOperations();

        return  $exchange->convertFromEur(
            self::ceilUp($amount / 100 * config('percentages.clients_withdraw.private.fee')),
            $rates,
            $convertTo
        );
    }

    private static function ceilUp($amount): float
    {
        return round($amount * 100, 1) / 100;
    }
}
