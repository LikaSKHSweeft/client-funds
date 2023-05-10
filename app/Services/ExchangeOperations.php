<?php

namespace App\Services;

class ExchangeOperations
{
    /**
     * @throws \Exception
     */
    public function calculateExchangeRate($data, $rates): float|null
    {
        if (isset($rates[$data['currency']])) {
            return 1 / $rates[$data['currency']] * $data['amount'];
        } else {
            throw new \Exception('Incorrect currency provided!', 403);
        }
    }
}
