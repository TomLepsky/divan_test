<?php

namespace App\Tests;

use App\Bank\Exchanger\BankExchanger;
use App\Currency\EUR;
use App\Currency\RUB;
use PHPUnit\Framework\TestCase;

class ExchangerTest extends TestCase
{
    public function testExchangeRate() : void
    {
        $exchanger = new BankExchanger();
        $exchanger->setRate(EUR::class, RUB::class, 80);
        $rate = $exchanger->getRate(EUR::class, RUB::class);
        $this->assertEqualsWithDelta(80, $rate, 0.001);
    }

    public function testExchangeChangeRate() : void
    {
        $exchanger = new BankExchanger();
        $exchanger->setRate(EUR::class, RUB::class, 80);
        $exchanger->setRate(EUR::class, RUB::class, 60);
        $rate = $exchanger->getRate(EUR::class, RUB::class);
        $this->assertEqualsWithDelta(60, $rate, 0.001);
    }
}