<?php

namespace App\Bank;

use App\Account\{Account, AccountMap, IndividualAccount};
use App\Bank\Exchanger\{BankExchanger, Exchanger};
use App\Currency\{Currency, Multicurrency};
use App\Owner\Owner;
use Exception;

class Bank implements Multicurrency
{
    protected AccountMap $accountMap;

    /** @var array<string> */
    protected array $currencies = [];

    protected string $mainCurrency;

    protected Exchanger $exchanger;

    public function __construct()
    {
        $this->accountMap = new AccountMap();
        $this->exchanger = new BankExchanger();
    }

    public function createAccount(Owner $accountOwner) : IndividualAccount
    {
        $newAccount = new IndividualAccount($accountOwner, $this);
        $this->accountMap->add($newAccount);

        return $newAccount;
    }

    public function attachAccount(Account $account) : bool
    {
        return $this->accountMap->add($account);
    }

    public function addCurrency(string $currency) : self
    {
         if (!in_array($currency, $this->currencies)) {
             $this->currencies[] = $currency;
             if (count($this->currencies) === 1) {
                 $this->mainCurrency = $currency;
             }
         }
         return $this;
    }

    /**
     * @throws Exception
     */
    public function removeCurrency(string $currency): self
    {
        if (count($this->currencies) < 1) {
            throw new Exception("Bank: You must have at least one currency");
        }

        if ($this->mainCurrency === $currency) {
            throw new Exception("Bank: You cannot remove main currency");
        }

        if ($this->availableCurrency($currency)) {
            /** @var IndividualAccount $account */
            foreach ($this->accountMap->generator() as $account) {
                if ($account->getMainCurrency() === $currency) {
                    $account->addCurrency($this->mainCurrency);
                    $account->setMainCurrency($this->mainCurrency);
                }
                $account->removeCurrency($currency);
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

    /**
     * @throws Exception
     */
    public function exchange(Currency $from, string $to) : Currency
    {
        if ($this->availableCurrency($from::class) && $this->availableCurrency($to)) {
            return $this->exchanger->convert($from, $to);
        }
        throw new Exception("Bank: This currency is not available to exchange in bank");
    }

    public function availableCurrency(string $currency) : bool
    {
        return in_array($currency, $this->currencies);
    }

    public function setExchangeRate(string $fromCurrency, string $toCurrency, int $rate) : self
    {
        $this->exchanger->setRate($fromCurrency, $toCurrency, $rate);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getExchangeRate(string $fromCurrency, string $toCurrency) : float
    {
        return $this->exchanger->getRate($fromCurrency, $toCurrency);
    }

    public function setMainCurrency(string $mainCurrency): void
    {
        if (in_array($mainCurrency, $this->currencies)) {
            $this->mainCurrency = $mainCurrency;
        }
    }
}