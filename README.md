# Package array

![PHP Array package](https://raw.githubusercontent.com/Hi-Folks/array/main/cover-arr.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hi-folks/array.svg?style=flat-square)](https://packagist.org/packages/hi-folks/array)
[![Tests](https://github.com/hi-folks/array/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/hi-folks/array/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/hi-folks/array.svg?style=flat-square)](https://packagist.org/packages/hi-folks/array)

**Arr** class is built on top of the PHP array functions.

**Arr** exposes methods to create, manage, access the data structure of the array.

The interface (method names, method arguments) are pretty similar to the Javascript Array class.

I built this class because comparing method functions arrays of Javascript and PHP i think (my personal thought) that the JS one is smoother and has a good developer experience (but, again, it's a personal opinion).



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
