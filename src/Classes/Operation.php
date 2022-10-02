<?php

namespace HiFolks\DataType\Classes;

use Closure;

class Operation
{
    public static function sum($field): Closure
    {
        return fn ($result, $element) => $result + $element[$field];
    }
}
