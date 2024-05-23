<?php

namespace HiFolks\DataType\Classes;

use Closure;

class Operation
{
    public static function add(int|string $field, int|float $value): Closure
    {
        return fn ($element): int|float => $value + $element[$field];
    }

    public static function double(int|string $field): Closure
    {
        return fn ($element): int|float => $element[$field] * 2;
    }
}
