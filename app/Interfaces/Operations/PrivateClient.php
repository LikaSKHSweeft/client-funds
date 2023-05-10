<?php

namespace App\Interfaces\Operations;

use App\Services\ExchangeOperations;
use App\Traits\PercentageCalculatorTrait;

class PrivateClient implements OperandClientInterface
{
    private ExchangeOperations $service;

    use PercentageCalculatorTrait;

    public function __construct()
    {
        $this->service = new ExchangeOperations();
    }

    public function deposit($amount): float
    {
        return $this->depositPercentageCalculator($amount);
    }

    /**
     * @throws \Exception
     */
    public function withdraw($data): array
    {
        $calculatedCommissions = [];

        foreach ($data as $client) {
            //sort by date, in case dates are mixed
            $client = collect($client)->sortBy([
                fn(array $a, array $b) => $a['date'] <=> $b['date']
            ]);

            $commissions = [];

            foreach ($client as $operation) {
                $key = $this->generateKey($operation['date']);

                $commissions[$key] = $this->buildCommissionsData($key, $operation, $commissions);

                $calculatedCommissions[$operation['order']] = $this->validateCommission(
                    $operation['amount'],
                    $commissions[$key]
                );
            }
        }

        return $calculatedCommissions;
    }

    private function validateCommission($amount, $commission): float
    {
        if ($commission['count'] <= 3 && $commission['amount'] <= 1000) {
            return 0;
        }

        if ($commission['count'] > 3) {
            return $this->withdrawPercentageCalculator($amount);
        }

        $lastOperation = $commission['amount'] - $amount;

        return $this->withdrawPercentageCalculator(
            $lastOperation >= 1000 ?
                $amount : $commission['amount'] - 1000
        );
    }

    private function generateKey($date): string
    {
        return date("Y-m-d", strtotime('monday this week', strtotime($date)))
            . '-' .
            date("Y-m-d", strtotime('sunday this week', strtotime($date)));
    }

    private function buildCommissionsData($key, $operation, $commissions): array
    {
        return [
            'amount' => isset($commissions[$key]['amount']) ?
                $commissions[$key]['amount'] + $operation['amount'] : $operation['amount'],
            'count' => isset($commissions[$key]['count']) ? ++$commissions[$key]['count'] : 1
        ];
    }
}
