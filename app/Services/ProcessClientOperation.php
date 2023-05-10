<?php

namespace App\Services;

use App\Http\Requests\ProcessClientOperationRequest;
use App\Imports\ClientOperationsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProcessClientOperation
{
    public function process(ProcessClientOperationRequest $request): \Illuminate\Support\Collection
    {
        $data = $request->validated();
        $commissions = new ClientOperationsImport();

        Excel::import($commissions, $data['operations']);

        return collect($commissions->getCommissions())->map(function ($e) {
            return number_format($e, 2);
        });
    }
}
