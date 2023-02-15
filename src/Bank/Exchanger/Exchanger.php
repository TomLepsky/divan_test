<?php

namespace App\Bank\Exchanger;

use App\Currency\Currency;

interface Exchanger
{
    public function setRate(string $fromCurrency, string $toCurrency, int $rate) : void;

    public function getRate(string $fromCurrency, string $toCurrency) : float;

    public function convert(Currency $from, string $to) : Currency;
}