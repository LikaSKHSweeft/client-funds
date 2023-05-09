<?php

namespace App\Interfaces\Operations;

use App\Traits\PercentageCalculatorTrait;

class BusinessClient implements OperandClientInterface
{
    use PercentageCalculatorTrait;

    public function deposit($amount): float
    {
        return $this->depositPercentageCalculator($amount);
    }

    public function withdraw($data, $rates = null): float
    {
        return $this->ceilUp($data['amount'] / 100 * config('percentages.clients_withdraw.business.fee'));
    }
}
