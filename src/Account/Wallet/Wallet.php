<?php

namespace App\Account\Wallet;

use App\Currency\Currency;

class Wallet
{
    private array $wallet = [];

    public function plus(Currency $currency) : void
    {
        if (key_exists($currency::class, $this->wallet)) {
            $this->wallet[$currency::class] += $currency->getAmount();
        } else {
            $this->wallet[$currency::class] = $currency->getAmount();
        }
    }

    public function minus(Currency $currency) : bool
    {
        if (
            key_exists($currency::class, $this->wallet) &&
            ($this->wallet[$currency::class] - $currency->getAmount() >= 0)
        ) {
                $this->wallet[$currency::class] -= $currency->getAmount();
                if ($this->wallet[$currency::class] === 0.0) {
                    unset($this->wallet[$currency::class]);
                }
                return true;
        }
        return false;
    }

    public function getMoney(string $currency) : ?Currency
    {
        if (!key_exists($currency, $this->wallet)) {
            return null;
        }
        $money = new $currency($this->wallet[$currency]);
        $this->minus($money);
        return $money;
    }

    public function showWallet(): array
    {
        return $this->wallet;
    }

}