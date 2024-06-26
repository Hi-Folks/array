# Package array

![PHP Array package](https://raw.githubusercontent.com/Hi-Folks/array/main/cover-arr.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hi-folks/array.svg?style=for-the-badge)](https://packagist.org/packages/hi-folks/array)
[![PHP Unit Tests](https://img.shields.io/github/actions/workflow/status/hi-folks/array/run-tests.yml?branch=main&style=for-the-badge)](https://github.com/Hi-Folks/array/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/hi-folks/array.svg?style=for-the-badge)](https://packagist.org/packages/hi-folks/array)
[![Test Coverage](https://raw.githubusercontent.com/Hi-Folks/array/main/badge-coverage.svg)](https://packagist.org/packages/hi-folks/array)

This package provides 2 classes:

- **[Arr](#arr-class)** class is built on top of the PHP array functions.
- **[Table](#table-class)** class allow you to manage bidimensional associative array (like a table or tuple).

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
- getArr(): get the element (as Arr::class instance) by index (helpful for nested arrays)
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
- set(): ability to set an element to the array with a specific key
- unset(): ability to unset an element by the key

### The `get()` method
The `get()` method supports keys/indexes with the dot (or custom) notation for retrieving values from nested arrays.
For example:

```php
$fruits = Arr::make([
    'green' => [
        'kiwi' => '🥝',
        'mango' => '🥭'
    ],
    'red' => [
        'strawberry' => '🍓',
        'apple' => '🍎'
    ],
    'yellow' => [
        'lemon' => '🍋',
        'banana' => '🍌',
    ]
]);
$fruits->get('red'); // 🍓,🍎
$fruits->get('red.strawberry'); // 🍓
```

You can customize the notation with a different character:

```php
$fruits->get('red#strawberry', charNestedKey: '#'); // 🍓
```
You can define a default value in the case the key doesn't exist:

```php
$fruits->get('red#somestrangefruit',
'🫠', '#'); // 🫠
```

### The `getArr()` method
If you need to manage a complex array (nested array), or an array obtained from a complex JSON structure, you can access a portion of the array and obtain an Arr object.
Just because in the case of a complex array the `get()` method could return a classic array.

Let's see an example:

```php
    $arr = Arr::make(
        [
            "avocado" =>
                [
                    'name' => 'Avocado',
                    'fruit' => '🥑',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Avocado'
                ],
            "apple" =>
                [
                    'name' => 'Apple',
                    'fruit' => '🍎',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Apple'
                ],
            "banana" =>
                [
                    'name' => 'Banana',
                    'fruit' => '🍌',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Banana'
                ],
            "cherry" =>
                [
                    'name' => 'Cherry',
                    'fruit' => '🍒',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Cherry'
                ],
        ]
    );
$appleArr = $arr->getArr("apple")
// $appleArr is an Arr instance so that you can access
// to the Arr methods like count()
$arr->getArr("apple")->count();

```
### The `set()` method
The `set()` method supports keys with the dot (or custom) notation for setting values for nested arrays.
If a key doesn't exist, the `set()` method will create a new key and will set the value.
If a key already exists, the `set()` method will replace the value related to the key.

For example:

```php
$articleText = "Some words as a sample sentence";
$textField = Arr::make();
$textField->set("type", "doc");
$textField->set("content.0.content.0.text", $articleText);
$textField->set("content.0.content.0.type", "text");
$textField->set("content.0.type", "paragraph");
```

So when you try to set a nested key as "content.0.content.0.text", it will be created elements as a nested array.
So if you try to dump the value of the array of `$textField` you will see the following structure:

```
var_dump($textField->arr());

array(2) {
  ["type"]=>
  string(3) "doc"
  ["content"]=>
  array(1) {
    [0]=>
    array(2) {
      ["content"]=>
      array(1) {
        [0]=>
        array(2) {
          ["text"]=>
          string(31) "Some words as a sample sentence"
          ["type"]=>
          string(4) "text"
        }
      }
      ["type"]=>
      string(9) "paragraph"
    }
  }
}
```

## Table class
Table class allows you to manage bi-dimensional array, something like:
```
[
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
]
```

Each row within the Table will be of type `Arr` so it allows you to lean on all the methods that
are available via the `Arr` object.

**Table class** allows you to filter, order, select some fields, create calculated fields.
The methods:
- select(): select some fields
- except(): exclude some fields
- where(): filter data
- groupBy(): grouping data
- transform(): transforms a specific field with the provided function
- orderBy(): sorting data (ascending or descending)
- toArray(): transform Table object into a native PHP array


`Table` now implements `\Countable` and `\Iterator`, this allows you to count the number of rows
and also loop over the rows using common loops.


## Installation

You can install the package via composer:

```bash
composer require hi-folks/array
```


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
use HiFolks\DataType\Table;
$dataTable = [
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
];
$table = Table::make($dataTable);
$arr = $table
    ->select('product' , 'price')
    ->where('price', ">", 100)
    ->calc('new_field', fn ($item) => $item['price'] * 2);
```

The result is
```
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
