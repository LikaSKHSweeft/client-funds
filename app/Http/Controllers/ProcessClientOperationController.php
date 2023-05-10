<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessClientOperationRequest;
use App\Services\ProcessClientOperation;

class ProcessClientOperationController extends Controller
{
    private ProcessClientOperation $operationService;

    public function __construct(ProcessClientOperation $operationService)
    {
        $this->operationService = $operationService;
    }

    public function __invoke(ProcessClientOperationRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $this->operationService->process($request)
        ]);
    }
}
