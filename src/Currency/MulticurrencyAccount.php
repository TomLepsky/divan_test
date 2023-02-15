<?php

namespace App\Currency;

interface MulticurrencyAccount extends Multicurrency
{
    public function setMainCurrency(string $currency) : self;

    public function getMainCurrency() : string;
}