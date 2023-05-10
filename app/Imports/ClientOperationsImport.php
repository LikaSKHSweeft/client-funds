<?php

namespace App\Imports;

use App\Enums\ClientType;
use App\Enums\OperationType;
use App\Interfaces\Currency\CurrencyProviderFactory;
use App\Interfaces\Operations\OperandClientFactory;
use App\Services\ExchangeOperations;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ClientOperationsImport implements ToCollection, WithHeadingRow, WithValidation
{
    private array $rates;
    private array $clients;
    private array $commissions;

    public function __construct()
    {
        $this->rates = [];
        $this->clients = [];
        $this->commissions = [];
    }

    /**
     * @return array
     */
    public function getCommissions(): array
    {
        return $this->commissions;
    }

    /**
     * @param Collection $collection
     * @throws \Exception
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            if (strtoupper($row['operation_type']) == OperationType::DEPOSIT->name) {
                $this->commissions[$key] = OperandClientFactory::getOperandClient(
                    strtoupper($row['client_type'])
                )->deposit(
                    $row['amount']
                );
            } else {
                if (!sizeof($this->rates)) {
                    $this->rates = CurrencyProviderFactory::getCurrencyRateProvider()->call();
                }

                switch (strtoupper($row['client_type'])) {
                    case ClientType::BUSINESS->name:
                        $this->commissions[$key] = OperandClientFactory::getOperandClient(
                            ClientType::BUSINESS->name
                        )->withdraw($row);
                        break;
                    case ClientType::PRIVATE->name:
                        $this->handlePrivateWithdraw($row, $key, count($collection));
                        break;
                }
            }
        }
    }

    public function rules(): array
    {
        return [
            'client_type' => ['required', Rule::in(['private', 'business'])],
            'operation_type' => ['required', Rule::in(['withdraw', 'deposit'])],
            'currency' => 'required',
            'client' => 'required',
            'date' => 'required',
            'amount' => 'required',
        ];
    }

    /**
     * @throws \Exception
     */
    private function setClientsData($row, $key)
    {
        $exchange = new ExchangeOperations();

        $this->clients[$row['client']][] = [
            'date' => $row['date'],
            'amount' => $exchange->calculateExchangeRate($row, $this->rates),
            'order' => $key
        ];
    }

    /**
     * @throws \Exception
     */
    private function handlePrivateWithdraw($row, $key, $count)
    {
        $this->setClientsData($row, $key);

        if ($count - 1 <= $key) {
            $data = OperandClientFactory::getOperandClient(
                ClientType::PRIVATE->name
            )->withdraw($this->clients);

            $this->commissions = array_merge($data, $this->commissions);
        }
    }
}
