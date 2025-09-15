<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Immutable Money value object (stores integer minor units to avoid floating point drift).
 * Assumes 2 decimal places for fiat currency. Extend for multi-currency later.
 */
class Money
{
    private int $amount; // minor units (cents)

    private const SCALE = 2;

    private function __construct(int $amountMinor)
    {
        $this->amount = $amountMinor;
    }

    public static function fromString(string $value): self
    {
        if (!preg_match('/^-?\d+(?:\.\d{1,2})?$/', $value)) {
            throw new InvalidArgumentException("Invalid money format: {$value}");
        }
        $parts = explode('.', $value, 2);
        $major = (int)$parts[0];
        $minor = isset($parts[1]) ? str_pad($parts[1], self::SCALE, '0') : '00';
        $sign = $major < 0 ? -1 : 1;
        $absMajor = abs($major);
        $minorInt = ($absMajor * 100) + (int)$minor;
        return new self($sign * $minorInt);
    }

    public static function fromFloat(float $value): self
    {
        return self::fromString(number_format($value, self::SCALE, '.', ''));
    }

    public static function fromMinor(int $minor): self
    {
        return new self($minor);
    }

    public function toMinor(): int
    {
        return $this->amount;
    }

    public function toFloat(): float
    {
        return $this->amount / 100;
    }

    public function toString(): string
    {
        $sign = $this->amount < 0 ? '-' : '';
        $abs = abs($this->amount);
        $major = intdiv($abs, 100);
        $minor = str_pad((string)($abs % 100), 2, '0', STR_PAD_LEFT);
        return $sign . $major . '.' . $minor;
    }

    public function add(self $other): self
    {
        return new self($this->amount + $other->amount);
    }

    public function subtract(self $other): self
    {
        return new self($this->amount - $other->amount);
    }

    public function multiply(float $factor): self
    {
        $result = (int) round($this->amount * $factor);
        return new self($result);
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
