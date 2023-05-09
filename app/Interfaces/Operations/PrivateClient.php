<?php

namespace App\Interfaces\Operations;

use App\Interfaces\Currency\CurrencyProviderFactory;
use App\Services\PrivateClientOperations;
use App\Traits\PercentageCalculatorTrait;

class PrivateClient implements OperandClientInterface
{
    private PrivateClientOperations $service;

    use PercentageCalculatorTrait;

    public function __construct()
    {
        $this->service = new PrivateClientOperations();
    }

    public function deposit($amount): float
    {
        return $this->depositPercentageCalculator($amount);
    }

    public function withdraw($data, $rates)
    {
        $data = $this->service->calculateExchange($rates, $data);

        dump($data);
    }
}
