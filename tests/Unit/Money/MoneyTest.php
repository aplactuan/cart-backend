<?php

namespace Tests\Unit\Money;

use App\Cart\Money;
use Money\Money as BaseMoney;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_gets_the_raw_amount()
    {
        $money = new Money(1000);
        $this->assertEquals(1000, $money->amount());
    }

    public function test_it_gets_the_formatted_value()
    {
        $money = new Money(1000);
        $this->assertEquals('$10.00', $money->formatted());
    }

    public function test_it_adds_two_money()
    {
        $money = new Money(1000);
        $money->add(new Money(2000));

        $this->assertEquals(3000, $money->amount());
    }

    public function test_it_returns_the_base_money_instance()
    {
        $money = new Money(1000);
        $this->assertInstanceOf(BaseMoney::class, $money->instance());
    }
}
