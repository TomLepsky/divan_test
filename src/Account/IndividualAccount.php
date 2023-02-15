<?php

namespace App\Account;

use App\Currency\Currency;
use App\Currency\MulticurrencyAccount;
use Exception;

class IndividualAccount extends Account implements MulticurrencyAccount
{
    protected ?string $mainCurrency = null;

    protected array $currencies = [];

    /**
     * @throws Exception
     */
    public function deposit(Currency $currency): self
    {
        if (!in_array($currency::class, $this->currencies)) {
            throw new Exception("Account: Currency " . $currency::class . " is not attached to this account");
        }

        $this->wallet->plus($currency);
        return $this;
    }

    /**
     * @param Currency $currency
     * @return Currency
     * @throws Exception
     */
    public function withdraw(Currency $currency): Currency
    {
        if (!$this->wallet->minus($currency)) {
            throw new Exception("Account: Not enough money");
        }
        return new ($currency::class)($currency->getAmount());
    }

    public function addCurrency(string $currency, bool $mainCurrency = false) : self
    {
        if (in_array($currency, $this->bank->getCurrencies()) && (!in_array($currency, $this->currencies))) {
            $this->currencies[] = $currency;
            if (count($this->currencies) === 1) {
                $this->setMainCurrency($currency);
            }
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function removeCurrency(string $currency) : self
    {
        if ($this->mainCurrency === $currency) {
            throw new Exception("Account: you cannot remove main currency");
        }

        if (in_array($currency, $this->currencies)) {
            if (($money = $this->wallet->getMoney($currency)) !== null) {
                $exchanged = $this->bank->exchange($money, $this->mainCurrency);
                $this->wallet->plus($exchanged);
            }

            $this->currencies = array_filter($this->currencies, function (string $cur) use ($currency) : bool {
                return $cur !== $currency;
            });
        }

        return $this;
    }

    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    public function setMainCurrency(string $currency) : self
    {
        if (in_array($currency, $this->currencies)) {
            $this->mainCurrency = $currency;
        }
        return $this;
    }

    public function getMainCurrency(): string
    {
        return $this->mainCurrency;
    }

    public function balance(string $currency = ''): Currency
    {
        if ($currency === '') {
            $currency = $this->mainCurrency;
        }
        $money = 0.0;
        if (key_exists($currency, $this->wallet->showWallet())) {
            $money = $this->wallet->showWallet()[$currency];
        }
        return  new $currency($money);
    }

    public function __toString() : string
    {
        $acc = [
            'id' => $this->id,
            'owner' => $this->owner->name,
            'attached_currencies' => $this->currencies,
            'main_currency' => $this->mainCurrency,
            'wallet' => $this->wallet->showWallet()
        ];
        return print_r($acc, true);
    }
}