<?php

namespace App\Cart;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;
use NumberFormatter;

class Money
{
    protected $money;

    public function __construct($money)
    {
        $this->money = new BaseMoney($money, new Currency('USD'));
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('en_US', NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }
}
