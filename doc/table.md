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
The `groupBy()` method groups rows by a field value and returns a **Table of Tables**. Each entry in the returned Table is itself a Table containing all rows that share the same field value.

```php
use HiFolks\DataType\Table;

$table = Table::make([
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]);
$firstItem = $table->first();
var_dump($firstItem);
$grouped = $table->groupBy('product');

// $grouped contains 4 groups: Desk, Chair, Door, Bookcase
// Each group is a Table with all matching rows
count($grouped);          // 4
count($grouped->first()); // 1 (Desk has 1 row)

// Iterate over the groups
foreach ($grouped as $key => $groupTable) {
    echo $key . ': ' . count($groupTable) . ' rows' . PHP_EOL;
}
// Output:
// 'Desk': 1 rows
// 'Chair': 1 rows
// 'Door': 2 rows
// 'Bookcase': 1 rows
```

You can combine `groupBy()` with other methods. For example, to get the cheapest active product in each group:

```php
$cheapest = $table
    ->where('active', '=', true)
    ->orderBy('price', 'asc')
    ->groupBy('product');

// Each group's first row is the cheapest for that product
$cheapest->first()->first()->get('price'); // 100 (Chair)
```

### Key mode

The `groupBy()` method accepts an optional second parameter to control how the group keys are generated. The `GroupByKeyMode` enum provides three options:

```php
use HiFolks\DataType\Enums\GroupByKeyMode;
```

- **`GroupByKeyMode::VarExport`** (default): uses `var_export()` to generate keys. This avoids collisions between values like `true` and `1` or `false` and `0`, because they produce distinct string keys (`'true'` vs `'1'`).
- **`GroupByKeyMode::Strval`**: uses `strval()` to generate keys. This produces cleaner string keys (e.g. `'Desk'` instead of `"'Desk'"`), but `true` and `1` will collide (both become `"1"`).
- **`GroupByKeyMode::Int`**: uses `intval()` to generate integer keys. Useful for numeric grouping or when you want to truncate float values (e.g. `1.5` and `1.9` both become key `1`).

```php
// Default (VarExport): keys are "'Desk'", "'Chair'", etc.
$grouped = $table->groupBy('product');

// Strval: keys are "Desk", "Chair", etc.
$grouped = $table->groupBy('product', GroupByKeyMode::Strval);

// Int: keys are integer values, e.g. 200, 100, 300, 150
$grouped = $table->groupBy('price', GroupByKeyMode::Int);
```

Non-scalar field values (arrays, objects) and `null` values are skipped during grouping.


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
