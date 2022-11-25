<?php

require './vendor/autoload.php';

use HiFolks\DataType\Table;

$table = Table::make([
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]);

echo PHP_EOL.'------- Filter price > 100'.PHP_EOL;
var_export(
    $table
    ->select('product', 'price')
    ->where('price', '>', 100)
);
/*
HiFolks\DataType\Table::__set_state(array(
   'rows' =>
      array (
        0 =>
        HiFolks\DataType\Arr::__set_state(array(
           'arr' =>
          array (
            'product' => 'Desk',
            'price' => 200,
          ),
        )),
        1 =>
        HiFolks\DataType\Arr::__set_state(array(
           'arr' =>
          array (
            'product' => 'Door',
            'price' => 300,
          ),
        )),
        2 =>
        HiFolks\DataType\Arr::__set_state(array(
           'arr' =>
          array (
            'product' => 'Bookcase',
            'price' => 150,
          ),
        )),
      ),
))
 */

echo PHP_EOL.'------- Filter and calculate new field'.PHP_EOL;
$result = $table
    ->select('product', 'price')
    ->where('price', '>', 100)
    ->calc('new_field', fn ($item) => $item['price'] * 2);
var_export($result);
/*
HiFolks\DataType\Table::__set_state(array(
   'rows' =>
  array (
    0 =>
    HiFolks\DataType\Arr::__set_state(array(
       'arr' =>
      array (
        'product' => 'Desk',
        'price' => 200,
        'new_field' => 400,
      ),
    )),
    1 =>
    HiFolks\DataType\Arr::__set_state(array(
       'arr' =>
      array (
        'product' => 'Door',
        'price' => 300,
        'new_field' => 600,
      ),
    )),
    2 =>
    HiFolks\DataType\Arr::__set_state(array(
       'arr' =>
      array (
        'product' => 'Bookcase',
        'price' => 150,
        'new_field' => 300,
      ),
    )),
  ),
))
 */

// Select only the required columns from the Table
echo PHP_EOL.'-------'.PHP_EOL;
var_export($table->select('product', 'price'));

// Select all but the columns mentioned from the Table
echo PHP_EOL.'-------'.PHP_EOL;
var_export($table->except('active', 'price'));

// Group the Table by the column
echo PHP_EOL.'-------'.PHP_EOL;
var_export($table->groupBy('product'));

// Order the table by the column
echo PHP_EOL.'-------'.PHP_EOL;
var_export($table->orderBy('product'));

$table = Table::make([
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]);

print_r($table->toArray());
