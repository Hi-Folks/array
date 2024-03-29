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

By default, the order is desc. If you want to order in descending order you can use *orderBy($field, $order)* method.

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
    $table->orderBy('price', 'asc')
);
/*
[
    ['product' => 'Door', 'price' => 100, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
]
*/
```

Both orders can be inforced by using `asc` and `desc`

## Group By a column
For example if you want to group the products by their `product` value you can use *groupBy()* method.

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
    $table->groupBy('product');
);
/*
[
    ['product' => 'Door', 'price' => 100, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Desk', 'price' => 200, 'active' => true]
]
*/
```

The `groupBy` method will always keep the first one that it finds, so if you want to keep only the product
that costs the most you'll have to use the `orderBy` method before the `groupBy` method.


## Transform a column with a custom function
For example if you want to transform all values in a column within the table you can use *transform()* method.

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
    $table->transform('price', function ($value) {
        return $value * 100;
    })
);
/*
[
    ['product' => 'Door', 'price' => 10000, 'active' => true],
    ['product' => 'Chair', 'price' => 10000, 'active' => true],
    ['product' => 'Bookcase', 'price' => 15000, 'active' => true],
    ['product' => 'Desk', 'price' => 20000, 'active' => true]
]
*/
```

This can be used if you wanted to turn the price from dollars into cent i.e 200 to 20000
