# Arr class
Arr is a class for managing PHP arrays, so you can use methods to perform some common operations.

## Description
Array in PHP is a basic but powerful type for managing list of elements.
Arrays in PHP are list of map key => value.
 Usually key are integer index, from 0.
In this case arrays are treated like an indexed array.
But keys could be "named" (integer, string...). In this case, arrays are treated like an associative array.

## Create an array
Create an array with static method *make()* and then you can access to all Arr methods, for example *length()*:
```php
use HiFolks\DataType\Arr;

$fruits = Arr::make(['🍎', '🍌']);
echo $fruits->length();
// Output: 2
```

## Access an Array item
Access an array item using the index position.
Even if you are using Arr class, you can access to the element with square brackets [] like with PHP standard array:
- $fruits[0];
- $fruits[ $fruits->length() - 1];

```php
use HiFolks\DataType\Arr;
// Create an array
$fruits = Arr::make(['🍎', '🍌']);
// First element
$first = $fruits[0];
echo $first;
echo PHP_EOL . "--~--" . PHP_EOL;
// Last element
$last = $fruits[ $fruits->length() - 1];
echo $last;
echo PHP_EOL . "--~--" . PHP_EOL;
```

## Loop over an array
If you need to walk through an array you can use forEach() method. You can specify the function as argument of *forEach()* in order to manage each single element (with key)
```php
use HiFolks\DataType\Arr;
// Create an array
$fruits = Arr::make([
    'kiwi' =>'🥝',
    'fragola' => '🍓',
    'lemon' => '🍋',
    'mango' => '🥭',
    'apple' => '🍎',
    'banana' => '🍌']);
// Loop over an array
$fruits->forEach(function ($element, $key) {
    echo $key . " " . $element . PHP_EOL;
});
```

## Add an element

Add an item **to the end** of an array, with *push()* method:

```php
use HiFolks\DataType\Arr;
// Create some fruits
$fruits = Arr::make(['🥝','🍓','🍋','🥭','🍎','🍌']);
// Push a new fruit (peach)
$fruits->push('🍑');
// Loop over fruits
$fruits->forEach(function ($element, $key) {
    echo $key . " " . $element . PHP_EOL;
});
echo PHP_EOL . "--~--" . PHP_EOL;
```
## Remove an element from the end of array
Get and remove element at the end of the array with pop() method:

```php
use HiFolks\DataType\Arr;
// Create some fruits
$fruits = Arr::make(['🥝','🍓','🍋','🥭','🍎','🍌']);
// pop (retrieve and remove) last elements
$last = $fruits->pop();
$secondLast = $fruits->pop();
// Loop over fruits
$fruits->forEach(function ($element, $key) {
    echo $key . " " . $element . PHP_EOL;
});
echo "Last fruit: " . $last . PHP_EOL; // banana
echo "Second last fruit: " . $secondLast . PHP_EOL; // apple
```

## Remove an element from the beginning of the array
Get and remove the first element from the beginning of the array with *shift()* method:

```php
use HiFolks\DataType\Arr;
// Create some fruits
$fruits = Arr::make(['🥝','🍓','🍋','🥭','🍎','🍌']);
// pop (retrieve and remove) last elements
first = $fruits->first();
// Loop over fruits
$fruits->forEach(function ($element, $key) {
    echo $key . " " . $element . PHP_EOL;
});
echo "First fruit: " . $first . PHP_EOL; // kiwi
```

## Add a new element to the beginning of the array
**Add** a new element **to the beginning** of the array with unshift() method:
```php
use HiFolks\DataType\Arr;
// Create some fruits
$fruits = Arr::make(['🥝','🍓','🍋','🥭','🍎','🍌']);
// add a new fruit (peach) before other fruits
$fruits->unshift('🍑');
// Loop over fruits
$fruits->forEach(function ($element, $key) {
    echo $key . " " . $element . PHP_EOL;
});
echo PHP_EOL . "--~--" . PHP_EOL;

```



