<?php

namespace App\Account;

use App\Account\Wallet\Wallet;
use App\Bank\Bank;
use App\Currency\Currency;
use App\Owner\Owner;

abstract class Account
{
    protected string $id;

    protected Wallet $wallet;

    public function __construct(protected Owner $owner, protected Bank $bank)
    {
        $this->id = uniqid(more_entropy: true);
        $this->wallet = new Wallet();
    }

    abstract public function deposit(Currency $currency) : self;

    abstract public function withdraw(Currency $currency) : Currency;

    abstract public function balance(string $currency = '') : Currency;

    public function getId(): string
    {
        return $this->id;
    }

    public function getOwner(): Owner
    {
        return $this->owner;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }
}