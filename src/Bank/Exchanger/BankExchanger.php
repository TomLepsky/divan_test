<?php

namespace App\Bank\Exchanger;

use App\Currency\Currency;
use Exception;

class BankExchanger implements Exchanger
{
    protected array $rates = [];

    private function findKey(string $fromCurrency, string $toCurrency) : ?string
    {
        $fromCurrency = $this->getCurrencyClassName($fromCurrency);
        $toCurrency = $this->getCurrencyClassName($toCurrency);

        foreach (array_keys($this->rates) as $currencies) {
            if (preg_match("/^($fromCurrency|$toCurrency)-($toCurrency|$fromCurrency)$/", $currencies, $matches)) {
                return $matches[0];
            }
        }
        return null;
    }

    private function createKey(string $fromCurrency, string $toCurrency) : string
    {
        $fromCurrency = $this->getCurrencyClassName($fromCurrency);
        $toCurrency = $this->getCurrencyClassName($toCurrency);

        return "$fromCurrency-$toCurrency";
    }

    private function getCurrencyClassName(string $class) : string
    {
        preg_match("/[A-Z]{3}$/", $class, $matches);
        return $matches[0];
    }

    /**
     * @throws Exception
     */
    public function convert(Currency $from, string $to) : Currency
    {
        if (!class_exists($to)) {
            throw new Exception("Exchanger: Currency $to doesnt exists");
        }

        $rate = $this->getRate($from::class, $to);
        return new $to($from->getAmount() * $rate);
    }

    public function setRate(string $fromCurrency, string $toCurrency, int $rate) : void
    {
        if (($key = $this->findKey($fromCurrency, $toCurrency)) !== null) {
            unset($this->rates[$key]);
        }
        $this->rates[$this->createKey($fromCurrency, $toCurrency)] = $rate;
    }

    /**
     * @throws Exception
     */
    public function getRate(string $fromCurrency, string $toCurrency) : float
    {
        if (($key = $this->findKey($fromCurrency, $toCurrency)) === null) {
            throw new Exception("Exchanger: There is no exchange rate from $fromCurrency to $toCurrency");
        }

        return (strcasecmp($key, $this->createKey($fromCurrency, $toCurrency)) === 0) ? $this->rates[$key] : 1 / $this->rates[$key];
    }
}