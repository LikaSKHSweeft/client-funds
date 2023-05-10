<?php

return [
    'deposit' => env('DEPOSIT_PERCENTAGE', 0.03),
    'clients_withdraw' => [
        'private' => [
            'fee' => env('PR_CLIENTS_WITHDRAW_FEE', 0.3),
            'free_operations_per_week' => env('PR_CLIENTS_FREE_PER_WEEK_OP', 3),
            'free_amount_per_week' => env('PR_CLIENTS_FREE_AMOUNT_PER_WEEK_OP', 1000)
        ],
        'business' => [
            'fee' => env('BUSINESS_CLIENTS_WITHDRAW_FEE', 0.5)
        ]
    ]
];
