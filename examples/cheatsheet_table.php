<?php

require './vendor/autoload.php';

use HiFolks\DataType\Classes\Operation;
use HiFolks\DataType\Table;

$table = Table::make([
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]);
echo PHP_EOL.'------- Filter price > 100'.PHP_EOL;
var_export($table
    ->select(['product', 'price'])
    ->where('price', '>', 100)
    ->arr());
/*
array (
  1 => array (
    'product' => 'Chair',
    'price' => 100,
  ),
  4 => array (
    'product' => 'Door',
    'price' => 100,
  ),
)
*/

echo PHP_EOL.'------- Filter and calculate new field'.PHP_EOL;
var_export(
    $table
        ->select(['product', 'price'])
        ->where('price', '>', 100)
        ->calc('new_field', fn ($item) => $item['price'] * 2)
        ->arr()
);
/*
array (
  0 =>
  array (
    'product' => 'Desk',
    'price' => 200,
    'new_field' => 400,
  ),
  2 =>
  array (
    'product' => 'Door',
    'price' => 300,
    'new_field' => 600,
  ),
  3 =>
  array (
    'product' => 'Bookcase',
    'price' => 150,
    'new_field' => 300,
  ),
)
 */
echo PHP_EOL.'-------'.PHP_EOL;
var_export(
    $table
        ->select(['product', 'price'])
        ->arr()
);
echo PHP_EOL.'-------'.PHP_EOL;
var_export(
    $table
        ->except(['active', 'price'])
        ->arr()
);
echo PHP_EOL.'-------'.PHP_EOL;
var_export(
    $table
        ->groupBy('product')
);

echo PHP_EOL.'-------'.PHP_EOL;
var_export(
    $table
        //->where('active')
        ->groupThenApply(
            'product',
            'total',
            Operation::sum('price'),
            0
        )
);

echo PHP_EOL.'-------'.PHP_EOL;
