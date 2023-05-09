<?php

namespace App\Interfaces\Operations;


use App\Enums\ClientType;

class OperandClientFactory
{
    public static function getOperandClient($client): OperandClientInterface
    {
        return match ($client) {
            ClientType::PRIVATE->name => new PrivateClient(),
            ClientType::BUSINESS->name  => new BusinessClient(),
            default => throw new \Exception('Client type should be private of business!'),
        };
    }
}
