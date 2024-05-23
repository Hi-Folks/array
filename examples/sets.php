<?php

require __DIR__ . '/vendor/autoload.php';

use HiFolks\DataType\Arr;

// https://medium.com/@alvaro.saburido/set-theory-for-arrays-in-es6-eb2f20a61848

$arrA = Arr::make([1, 3, 4, 5]);
$arrB = Arr::make([1, 2, 5, 6, 7]);

echo " --- Intersection" . PHP_EOL;
$intersection = $arrA->filter(fn ($x): bool => $arrB->includes($x));
echo $intersection->toString() . PHP_EOL;
