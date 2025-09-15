<?php

namespace App\Casts;

use App\Support\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        if ($value === null) {
            return null;
        }
        // Value may be string or numeric; ensure consistent string format.
        return Money::fromString(number_format((float)$value, 2, '.', ''));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }
        if ($value instanceof Money) {
            return [$key => $value->toString()];
        }
        if (is_numeric($value)) {
            return [$key => number_format((float)$value, 2, '.', '')];
        }
        if (is_string($value)) {
            return [$key => Money::fromString($value)->toString()];
        }
        throw new \InvalidArgumentException('Unsupported money value type for cast.');
    }
}
