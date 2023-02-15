<?php

namespace App\Tests;

use App\Account\Account;
use App\Bank\Bank;
use App\Currency\{EUR, RUB, USD};
use App\Owner\Owner;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testApp() : void
    {
        $bank = new Bank();
        $bank
            ->addCurrency(RUB::class)
            ->addCurrency(USD::class)
            ->addCurrency(EUR::class)
            ->setExchangeRate(EUR::class, RUB::class, 80)
            ->setExchangeRate(USD::class, RUB::class, 70)
            ->setExchangeRate(EUR::class, USD::class, 1);

        $account = $bank->createAccount(new Owner('Test'));

        $account
            ->addCurrency(RUB::class)
            ->addCurrency(EUR::class)
            ->addCurrency(USD::class)
            ->setMainCurrency(RUB::class)
            ->deposit(new RUB(1000))
            ->deposit(new EUR(50))
            ->deposit(new USD(50));

        $money = $account->withdraw(new RUB(1000));
        $account->deposit($bank->exchange($money, EUR::class));
        $bank->setExchangeRate(EUR::class, RUB::class, 120);
        $bank->removeCurrency(USD::class);
        $bank->removeCurrency(EUR::class);
        $this->assertEquals(11000, $account->balance()->getAmount());
    }
}