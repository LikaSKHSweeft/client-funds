<?php

namespace App\Services;

class PrivateClientOperations
{
    public function calculateExchange($rates, $data): float|null
    {
        if (isset($rates[$data['currency']])) {
            return 1 / $rates[$data['currency']] * $data['amount'];
        } else {
            return $data['amount'];
        }
    }

}
