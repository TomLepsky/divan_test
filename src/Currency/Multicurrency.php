<?php

namespace App\Currency;

interface Multicurrency
{
    public function addCurrency(string $currency) : self;

    public function removeCurrency(string $currency) : self;

    /**
     * @return array<string>
     */
    public function getCurrencies() : array;
}