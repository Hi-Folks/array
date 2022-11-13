<?php

namespace HiFolks\DataType\Classes;

use Closure;

class Operation
{
    public static function sum(int|string $field): Closure
    {
        return fn ($result, $element) => $result + $element[$field];
    }
}
