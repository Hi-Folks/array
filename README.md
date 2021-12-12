# Package array

![PHP Array package](https://raw.githubusercontent.com/Hi-Folks/array/main/cover-arr.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hi-folks/array.svg?style=flat-square)](https://packagist.org/packages/hi-folks/array)
[![Tests](https://github.com/hi-folks/array/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/hi-folks/array/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/hi-folks/array.svg?style=flat-square)](https://packagist.org/packages/hi-folks/array)

**Arr** class is built on top of the PHP array functions.
**Arr** exposes methods for creating, managing, accessing to the array data structure.
The interface (method names, method arguments) are pretty similar to the Javascript Array class.
I built this class because comparing method functions arrays of Javascript and PHP i think (my personal though) that the JS one is more fluent and has a good developer experience (but, I repeat, it is a personal opinion).



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
