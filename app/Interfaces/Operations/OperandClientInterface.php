<?php

namespace App\Interfaces\Operations;


interface OperandClientInterface
{
    public function deposit($amount);
    public function withdraw($data, $rates);
}
