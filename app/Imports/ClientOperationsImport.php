<?php

namespace App\Imports;

use App\Enums\ClientType;
use App\Enums\OperationType;
use App\Interfaces\Currency\CurrencyProviderFactory;
use App\Interfaces\Operations\OperandClientFactory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ClientOperationsImport implements ToCollection, WithHeadingRow, WithValidation
{
    private array $rates;

    public function __construct()
    {
        $this->rates = [];
    }

    /**
     * @param Collection $collection
     * @throws \Exception
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (strtoupper($row['operation_type']) == OperationType::DEPOSIT->name) {
                $deposit = OperandClientFactory::getOperandClient(strtoupper($row['client_type']))->deposit(
                    $row['amount']
                );

                dump($deposit);
            } else {
                if (!sizeof($this->rates)) {
                    $this->rates = CurrencyProviderFactory::getCurrencyRateProvider()->call();
                }

                $w = OperandClientFactory::getOperandClient(strtoupper($row['client_type']))->withdraw($row, $this->rates);
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
}
