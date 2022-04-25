# Table class
Table is a class for managing PHP bi-dimensional arrays like:

```
[
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]
```



## Description

Table class allows you to filter, select some fields, create calculated fields.

## Create a Table

```php
use HiFolks\DataType\Table;

$dataTable = [
['product' => 'Desk', 'price' => 200, 'active' => true],
['product' => 'Chair', 'price' => 100, 'active' => true],
['product' => 'Door', 'price' => 300, 'active' => false],
['product' => 'Bookcase', 'price' => 150, 'active' => true],
['product' => 'Door', 'price' => 100, 'active' => true],
];

$table = Table::make($dataTable);
```

## Select some data

```php
use HiFolks\DataType\Table;

$dataTable = [
['product' => 'Desk', 'price' => 200, 'active' => true],
['product' => 'Chair', 'price' => 100, 'active' => true]
];

$table = Table::make($dataTable)->select(['product', 'price']);

/*
array (
  0 =>
  array (
    'product' => 'Desk',
    'price' => 200,
  ),
  1 =>
  array (
    'product' => 'Chair',
    'price' => 100,
  )
)
 */
```

## Select some data

Return a new Table instance with the selected attributes.
IN this case only *product* and *price* are returned.

```php
use HiFolks\DataType\Table;

$dataTable = [
['product' => 'Desk', 'price' => 200, 'active' => true],
['product' => 'Chair', 'price' => 100, 'active' => true]
];

$table = Table::make($dataTable)->select(['product', 'price']);

/*
array (
  0 =>
  array (
    'product' => 'Desk',
    'price' => 200,
  ),
  1 =>
  array (
    'product' => 'Chair',
    'price' => 100,
  )
)
 */
```

With *except()* method are returned all the attributes except the columns listed as parameter

```php
use HiFolks\DataType\Table;

$dataTable = [
['product' => 'Desk', 'price' => 200, 'active' => true],
['product' => 'Chair', 'price' => 100, 'active' => true]
];

$table = Table::make($dataTable)->except(['active']);

/*
array (
  0 =>
  array (
    'product' => 'Desk',
    'price' => 200,
  ),
  1 =>
  array (
    'product' => 'Chair',
    'price' => 100,
  )
)
 */
```
