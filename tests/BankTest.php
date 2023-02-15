<?php

namespace App\Tests;

use App\Bank\Bank;
use App\Owner\Owner;
use App\Currency\{EUR, RUB, USD};
use PHPUnit\Framework\TestCase;

class BankTest extends TestCase
{
    public function testBankCurrencies() : void
    {
        $bank = new Bank();
        $bank->addCurrency(RUB::class)
            ->addCurrency(USD::class)
            ->addCurrency(EUR::class)
            ->setExchangeRate(EUR::class, RUB::class, 80)
            ->setExchangeRate(USD::class, RUB::class, 70)
            ->setExchangeRate(EUR::class, USD::class, 1);

        $account = $bank->createAccount(new Owner("Tom"));
        $account
            ->addCurrency(USD::class)
            ->deposit(new USD(1000));

        $bank->removeCurrency(USD::class);
        $this->assertEquals(70000, $account->balance()->getAmount());
    }
}