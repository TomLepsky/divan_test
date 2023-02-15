<?php

namespace App\Tests;

use App\Account\IndividualAccount;
use App\Bank\Bank;
use App\Currency\EUR;
use App\Currency\RUB;
use App\Currency\USD;
use App\Owner\Owner;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testDeposit() : void
    {
        $account = new IndividualAccount(new Owner('test'), (new Bank())->addCurrency(RUB::class));
        $account->addCurrency(RUB::class);
        $account->deposit(new RUB(1000));
        $this->assertEqualsWithDelta(1000.0, $account->balance()->getAmount(), 0.001);
    }

    public function testWithdraw() : void
    {
        $account = new IndividualAccount(new Owner('test'), (new Bank())->addCurrency(RUB::class));
        $account->addCurrency(RUB::class);
        $account->deposit(new RUB(1000));
        $account->withdraw(new RUB(500));
        $this->assertEqualsWithDelta(500.0, $account->balance()->getAmount(), 0.001);
    }

    public function testCurrencies() : void
    {
        $account = new IndividualAccount(
            new Owner('test'),
            (new Bank())
                ->addCurrency(RUB::class)
                ->addCurrency(EUR::class)
                ->addCurrency(USD::class)
        );
        $account->addCurrency(RUB::class)
            ->addCurrency(EUR::class)
            ->addCurrency(USD::class)
            ->removeCurrency(EUR::class);
        $this->assertNotContains(EUR::class, $account->getCurrencies());
    }
}