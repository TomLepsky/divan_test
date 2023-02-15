<?php

namespace App\Tests;

use App\Account\Wallet\Wallet;
use App\Currency\RUB;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testWalletPlus() : void
    {
        $wallet = new Wallet();
        $wallet->plus(new RUB(1000));
        $wallet->plus(new RUB(100));
        $this->assertEquals(1100, $wallet->getMoney(RUB::class)->getAmount());
    }

    public function testWalletMinus() : void
    {
        $wallet = new Wallet();
        $wallet->plus(new RUB(1000));
        $wallet->minus(new RUB(100));
        $this->assertEquals(900, $wallet->getMoney(RUB::class)->getAmount());
    }

}