<?php

namespace App\Traits;

trait PercentageCalculatorTrait
{
    public function depositPercentageCalculator($amount): float
    {
        return self::ceilUp($amount / 100 * config('percentages.deposit'));
    }

    private static function ceilUp($amount): float
    {
        return ceil($amount * 100) / 100;
    }
}
