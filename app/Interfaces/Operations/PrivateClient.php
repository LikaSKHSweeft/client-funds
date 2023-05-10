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

    /**
     * Handles deposit for private client
     * @param $amount
     * @return float
     */
    public function deposit($amount): float
    {
        return $this->depositPercentageCalculator($amount);
    }

    /**
     * Handles withdraw for private client
     * @throws \Exception
     */
    public function withdraw($data, $rates): array
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
                    $commissions[$key],
                    $operation['currency'],
                    $rates
                );
            }
        }

        return $calculatedCommissions;
    }

    /**
     * Validates how much commission fee should be set for transaction
     * @throws \Exception
     */
    private function validateCommission($amount, $commission, $currency, $rates): float
    {
        $operationsPerWeek = config('percentages.clients_withdraw.private.free_operations_per_week');
        $freeOperationsPerWeek = config('percentages.clients_withdraw.private.free_amount_per_week');

        if ($commission['count'] <= $operationsPerWeek && $commission['amount'] <= $freeOperationsPerWeek) {
            return 0;
        }

        if ($commission['count'] > $operationsPerWeek) {
            return $this->withdrawPercentageCalculator($amount, $rates, $currency);
        }

        $lastOperation = $commission['amount'] - $amount;

        return $this->withdrawPercentageCalculator(
            $lastOperation >= $freeOperationsPerWeek ?
                $amount : $commission['amount'] - $freeOperationsPerWeek,
            $rates,
            $currency
        );
    }

    /**
     * Generates key for array according to start and end dates of date of provided transaction
     * @param $date
     * @return string
     */
    private function generateKey($date): string
    {
        return date("Y-m-d", strtotime('monday this week', strtotime($date)))
            . '-' .
            date("Y-m-d", strtotime('sunday this week', strtotime($date)));
    }

    /**
     * Build data for commissions
     * @param $key
     * @param $operation
     * @param $commissions
     * @return array
     */
    private function buildCommissionsData($key, $operation, $commissions): array
    {
        return [
            'amount' => isset($commissions[$key]['amount']) ?
                $commissions[$key]['amount'] + $operation['amount'] : $operation['amount'],
            'count' => isset($commissions[$key]['count']) ? ++$commissions[$key]['count'] : 1
        ];
    }
}
