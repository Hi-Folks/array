<?php

declare(strict_types=1);

namespace HiFolks\DataType\Enums;

enum GroupByKeyMode
{
    case VarExport;
    case Strval;
    case Int;
}
