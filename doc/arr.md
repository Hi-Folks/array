# Arr class
Arr is a class for managing PHP arrays, so you can use methods to perform some common operations.

## Description
Array in PHP is a basic but powerful data structure for managing list of elements.
Arrays in PHP are list of map (pair of key => value).
 Usually keys are integer that start from 0 (first element has position/index 0, the second one has position/index 1 etc... ).
In this case arrays are treated like an indexed array.
But keys could be "named" (integer, string...). In this case, arrays are treated like an associative array.
For example:
- ['🍎', '🍌'] : is an array with 2 elements the first one with index 0 and the second one with index 1;
- ['apple' => '🍎', 'banana' => '🍌']: is an array with 2 element with the same **values** of the previous one, but different indexes. In this case the index 'apple' is related with value '🍎', the index 'banana' is related with value '🍌'.

## Create an array
You can create an array (using Arr class) with static method *make()* and then you can access to all *Arr object*  methods, for example *length()*:
```php
use HiFolks\DataType\Arr;
$fruits = Arr::make(['🍎', '🍌']);
echo $fruits->length();
// Output: 2
```

## Access an array element using the index position
Even if you are using *Arr* class, you can access to the element with square brackets [] like with PHP standard array:
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
If you need to walk through an array, you can use *forEach()* method.
You can specify the function as argument of *forEach()* method in order to manage each single element (with key)
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
    echo $key . " " . $element . "; ";
});
// kiwi 🥝; fragola 🍓; lemon 🍋; mango 🥭; apple 🍎; banana 🍌;
```

## Add an element to the end

Add an item **to the end** of an array with *push()* method:

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
Get and remove element **from the end** of the array with *pop()* method:

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
Get and remove the first element **from the beginning** of the array with *shift()* method:

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

## Recap add and remove elements

| Method    | Operation          | Where              | Description                                                          |
|-----------|--------------------|--------------------|----------------------------------------------------------------------|
| push()    | **add** element    | to the **end**     | Add an item **to the end** of an array                               |
| pop()     | **remove** element | from the **end**   | Get and remove element **from the end** of the array                 |
| shift()   | **remove** element | from the **begin** | Get and remove the first element **from the beginning** of the array |
| unshift() | **add** element    | to the **begin**   | **Add** a new element **to the beginning** of the array              |

## Find the index of an element in the array
To find the index of an element you can use *indexOf()* method:

```php
use HiFolks\DataType\Arr;
// Create some fruits
$fruits = Arr::make(['🥝','🍓','🍋','🥭','🍎','🍌']);
echo "All fruits:" . $fruits->join(",") . PHP_EOL;
// All fruits:🥝,🍓,🍋,🥭,🍎,🍌
// Find the index of an item in the Array
$pos = $fruits->indexOf('🍎');
echo "Find 🍎 at position: " . $pos . PHP_EOL;
// Find 🍎 at position: 4
```

## Remove an element by index position
After last example ($pos === 4), you can remove an element with *splice()* method, using *$pos* and the amount of elements as arguments.
In this case, if you want to remove just one fruit (the apple) at position 4:

```php
$removedFruits = $fruits->splice($pos, 1);
echo "Removed fruits: " . $removedFruits->join(",") . PHP_EOL;
echo "Remaining fruits:" . $fruits->join(",") . PHP_EOL;
// Removed fruits: 🍎
// Remaining fruits:🥝,🍓,🍋,🥭,🍌
```

## Remove elements from an index position
If you want to remove more elements (for example 10) from position 1:
```php
// Remove items from an index position
$removedFruits = $fruits->splice(1, 10);
echo "Removed fruits: " . $removedFruits->join(",") . PHP_EOL;
echo "Remaining fruits:" . $fruits->join(",") . PHP_EOL;
// Removed fruits: 🍓,🍋,🥭,🍌
// Remaining fruits:🥝
```

## Copy an array
You can use *splice()* method for copying (cloning) an array, with 0 as position and the length of the array as the amount of elements:
```php
$some = $removedFruits->slice(0, $removedFruits->length());
echo "Some Fruits: " . $some->join(",") . PHP_EOL;
// Some Fruits: 🍓,🍋,🥭,🍌
```


## Create array from arguments
The Arr::of() method creates a new Arr instance from a variable number of arguments, regardless of number or type of the arguments.

```php
use HiFolks\DataType\Arr;
// Create some fruits
$fruits = Arr::of('🥝','🍓','🍋','🥭','🍎','🍌');
echo $arr->length();
// 6
echo $arr[4];
// 🍎
```

## Extract keys
The keys() method returns a new array [] or Arr object that contains the keys for each index in the array.

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
// keys as array
$arrayOfKeys = $fruits->keys();
$arrOfKeys = $fruits->keys(true);
echo $arrOfKeys->join();
```

