<?php

namespace App\Traits;

trait PercentageCalculatorTrait
{
    public function depositPercentageCalculator($amount): float
    {
        return self::ceilUp($amount / 100 * config('percentages.deposit'));
    }

    public function withdrawPercentageCalculator($amount): float
    {
        return self::ceilUp($amount / 100 * config('percentages.clients_withdraw.private.fee'));
    }

    private static function ceilUp($amount): float
    {
        return number_format(ceil($amount * 100) / 100, 2);
    }
}
