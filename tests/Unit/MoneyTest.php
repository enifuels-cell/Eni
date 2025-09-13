<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_from_string_and_to_string_round_trip(): void
    {
        $m = Money::fromString('1234.56');
        $this->assertSame('1234.56', $m->toString());
        $this->assertSame(123456, $m->toMinor());
    }

    public function test_addition_and_subtraction(): void
    {
        $a = Money::fromString('10.10');
        $b = Money::fromString('5.05');
        $this->assertSame('15.15', $a->add($b)->toString());
        $this->assertSame('5.05', $a->subtract($b)->toString());
    }

    public function test_multiply_rounds_properly(): void
    {
        $a = Money::fromString('10.00');
        $this->assertSame('25.00', $a->multiply(2.5)->toString());
    }

    public function test_negative_values(): void
    {
        $a = Money::fromString('-1.23');
        $this->assertSame('-1.23', (string)$a);
    }
}
