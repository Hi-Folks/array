<?php

namespace HiFolks\DataType\Classes;

class Operation
{
    public static function sum($field)
    {
        return fn ($result, $element) => $result + $element[$field];
    }
}
