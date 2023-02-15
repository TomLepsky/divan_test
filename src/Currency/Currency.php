<?php

namespace App\Currency;

abstract class Currency
{
    protected float $amount;

    public function __construct(float $amount)
    {
        $this->amount = round($amount, 2);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}