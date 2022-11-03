# Package array

![PHP Array package](https://raw.githubusercontent.com/Hi-Folks/array/main/cover-arr.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hi-folks/array.svg?style=flat-square)](https://packagist.org/packages/hi-folks/array)
[![PHP Unit Tests](https://github.com/hi-folks/array/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/hi-folks/array/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/hi-folks/array.svg?style=flat-square)](https://packagist.org/packages/hi-folks/array)
[![Test Coverage](https://raw.githubusercontent.com/Hi-Folks/array/main/badge-coverage.svg)](https://packagist.org/packages/hi-folks/array)

This package provides 2 classes:

- **Arr** class is built on top of the PHP array functions.
- **Table** class allow you to manage bidimensional associative array (like a table or tuple).

## Arr class

**Arr** exposes methods to create, manage, access the data structure of the array.

The interface (method names, method arguments) are pretty similar to the Javascript Array class.

I built this class because comparing method functions arrays of Javascript and PHP i think (my personal thought) that the JS one is smoother and has a good developer experience (but, again, it's a personal opinion).

The Arr class provides some methods:
- make() create array;
- fromFunction(): create Arr from a function;
- fromValue(): create Arr from a value;
- length(): length/size of the array;
- arr(): returns data with the type PHP array
- get(): get the element by index
- Iterator methods: current(), next(), prev(), key(), valid(), rewind()
- forEach(): execute a function for each element;
- push(): add new element (at the end);
- pop(): remove an element (at the end);
- unshift(): add new element at the start;
- shift(): remove an element from the start;
- append(): append arrays to the current one;
- concat(): return new array joining more arrays, Arr objects or scalar variables;
- join(): joins all elements into a string;
- slice(): returns a sub array;
- indexOf(): find the first occurrence;
- lastIndexOf(): find the last occurrence;
- every(): all elements match a fn();
- some(): at least one element matches a fn();
- filter(): filter elements by a fn();
- map(): apply a fn() for each element;
- flat(): flat an array of arrays;
- flatMap(): map() and flat();
- fill(): fill an array (or a piece of an array);
- reduce(): calculate a fn() with the array as input;
- reduceRight(): like reduce(), but parsing the array in reverse order;
- reverse(): reverse the array;
- sort(): sort the array;
- splice(): changes content of arr removing, replacing and adding elements;
- toString(): the string representing the array (same as join(','));
- isArray(): check if the input is an array;
- from(): for creating new Arr from a string or array-like object;
- findIndex(): for finding the index of some element;
- find(): returns the first element in the array that satisfies the testing function;
- entries(): returns a new Arr object that contains the key/value pairs for each index in the array;
- copyWithin(): copies part of the array to a location but keeps the original length.
- isEmpty(): checks if provided array is empty or not;
- values(): it creates a new Arr object with the values of the current one (keys are skipped)

## Table class
Table class allows you to manage bi dimensional array, something like:
```
[
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]
```

Table class allows you to filter, order, select some fields, create calculated fields.


## Installation

You can install the package via composer:

```bash
composer require hi-folks/array
```

> Currently, this package is under development. It's **not** "production ready". It is to be considered "not production ready" until it is in version v0.0.x. When version 0.1.x will be released, it means that the package is considered stable.

## Usage

To see some examples, I suggest you to take a look to *examples/cheatsheet.php* file,where you can see a lot of example and use cases.

To start quickly
```php
// Load the vendor/autoload file
require("./vendor/autoload.php");
// import the Arr class:
use HiFolks\DataType\Arr;
// use static make method to create Arr object
$arr = Arr::make();
$arr->push('Hi');
$arr->push('Folks');
echo $arr->length();
// to access to the "native" PHP array:
print_r($arr->arr());
```
To create an array with random values:
```php
require("./vendor/autoload.php");
use HiFolks\DataType\Arr;
$arr = Arr::fromFunction(fn () => random_int(0, 100), 500);
```

You can access to the elements like a native array, but you have also Arr methods:
```php
require("./vendor/autoload.php");
use HiFolks\DataType\Arr;
$arr = Arr::make();
$arr[] = "First element";
$arr[] = "Second element";
$count = $arr->length();
// output: 2
$arr->reverse();
echo $arr[0];
// output: Second element
```
## Usage of Table class

Starting from:

```
[
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
] 
```
I would like to **filter** the rows with price greater than 100, **select** only "product" and "price" fields, and for each rows **create a new field** named "new_filed" that is a calculated field (doubling the price):
```php
$dataTable = [
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
];
$table = Table::make($dataTable);
$arr = $table
    ->select(['product' , 'price'])
    ->where('price', 100, ">")
    ->calc('new_field', fn ($item) => $item['price'] * 2)
    ->arr();

```

The result is
```
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
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Roberto B.](https://github.com/roberto-butti)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
