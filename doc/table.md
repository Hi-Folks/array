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
## Filter data
You can filter data with *where()* method.
You can specify:
- $field: the field name to filter (for example price);
- $operator: the operator "==", "===", ">", ">=", "<", "<=", "!==", "!=";
- the value

```php 
use HiFolks\DataType\Classes\Operation;
use HiFolks\DataType\Table;

$table = Table::make([
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]);
var_export($table
    ->select(['product' , 'price'])
    ->where('price', '>', 100)
    ->arr());

/*
array (
  0 => array (
    'product' => 'Desk',
    'price' => 200,
  ),
  2 => array (
    'product' => 'Door',
    'price' => 300,
  ),
  3 => array (
    'product' => 'Bookcase',
    'price' => 150,
  ),
)*/
```

## Order By a column
For example if you want to order the price for each product you can use *orderBy()* method.

```php
use HiFolks\DataType\Table;
use HiFolks\DataType\Classes\Operation;

$table = Table::make([
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]);
var_export(
    $table->orderBy('price')
);
/*
[
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]
*/
```

