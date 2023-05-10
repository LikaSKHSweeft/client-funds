<?php

namespace App\Services;

class ExchangeOperations
{
    /**
     * @throws \Exception
     */
    public function calculateExchangeRate($data, $rates): float|null
    {
        if($data['currency'] == 'EUR') return $data['amount'];

        if (isset($rates[$data['currency']])) {
            return $data['amount'] / $rates[$data['currency']];
        } else {
            throw new \Exception('Incorrect currency provided!', 403);
        }
    }


    /**
     * @throws \Exception
     */
    public function convertFromEur($amount, $rates, $convertTo): float|null
    {
        if($convertTo == 'EUR') return $amount;

        if (isset($rates[$convertTo])) {
            return $amount * $rates[$convertTo];
        } else {
            throw new \Exception('Incorrect currency provided!', 403);
        }
    }
}
