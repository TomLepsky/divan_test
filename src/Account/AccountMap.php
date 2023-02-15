<?php

namespace App\Account;

use Generator;

class AccountMap
{
    /**
     * @param array<string, Account> $accounts
     */
    protected array $accounts = [];

    public function add(Account $account) : bool
    {
        if (!key_exists($account->getId(), $this->accounts)) {
            $this->accounts[$account->getId()] = $account;
            return true;
        }
        return false;
    }

    public function get(Account $account) : ?Account
    {
        if (key_exists($account->getId(), $this->accounts)) {
            return $this->accounts[$account->getId()];
        }
        return null;
    }

    public function generator() : Generator
    {
        foreach ($this->accounts as $account) {
            yield $account;
        }
    }
}