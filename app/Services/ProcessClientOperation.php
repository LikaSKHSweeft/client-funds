<?php

namespace App\Services;

use App\Http\Requests\ProcessClientOperationRequest;
use App\Imports\ClientOperationsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProcessClientOperation
{
    public function process(ProcessClientOperationRequest $request): void
    {
        $data = $request->validated();

        Excel::import(new ClientOperationsImport(), $data['operations']);
    }
}
