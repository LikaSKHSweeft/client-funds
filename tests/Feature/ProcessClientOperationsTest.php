<?php

namespace Tests\Feature;

use App\Imports\ClientOperationsImport;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class ProcessClientOperationsTest extends TestCase
{
    /**
     *
     * Test returned value for calculated fees on provided exchanges
     */
    public function test_operations(): void
    {
        $import = new ClientOperationsImport();

        $import->setRates([
            'USD' => 1.1497,
            'JPY' => 129.53,
        ]);

        Excel::import($import, public_path('operations.csv'));

        $commissions = collect($import->getCommissions())->map(function ($e) {
            return number_format($e, 2);
        });

        assertEquals([
            "0.60",
            "3.00",
            "0.00",
            "0.06",
            "1.50",
            "0.00",
            "0.70",
            "0.30",
            "0.30",
            "3.00",
            "0.00",
            "0.00",
            "8,611.41"
        ], $commissions->toArray());
    }
}
