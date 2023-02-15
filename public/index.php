<?php

use App\Bank\Bank;
use App\Currency\{EUR, RUB, USD};
use App\Owner\Owner;

require_once dirname(__DIR__).'/vendor/autoload.php';

try {
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

    echo "<pre>";
    echo $account;
    echo "</pre>";
    
} catch (Exception $e) {
    echo $e->getMessage();
}



