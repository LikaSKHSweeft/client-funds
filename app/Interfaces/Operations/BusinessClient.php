<?php

namespace App\Interfaces\Operations;

use App\Traits\PercentageCalculatorTrait;

class BusinessClient implements OperandClientInterface
{
    use PercentageCalculatorTrait;

    /**
     * Handles deposit for business client
     * @param $amount
     * @return float
     */
    public function deposit($amount): float
    {
        return $this->depositPercentageCalculator($amount);
    }

    /**
     * Handles withdraw for business client
     * @param $data
     * @param $rates
     * @return float
     */
    public function withdraw($data, $rates = null): float
    {
        return $this->ceilUp($data['amount'] / 100 * config('percentages.clients_withdraw.business.fee'));
    }
}
