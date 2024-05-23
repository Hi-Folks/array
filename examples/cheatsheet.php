<?php

require __DIR__ . '/../vendor/autoload.php';

use HiFolks\DataType\Arr;

$arr = Arr::make([1, 2, 3, 4, 5, 6]);

// Returns new Arr joining 2 arrays together (the current one and a new one)
print_result($arr->concat([10, 11, 12]));

print_result($arr->concat([10, 11, 12], 13, [14, 15]));
print_result($arr->concat($arr, $arr));

$fruits = Arr::make([
    3 => '🥝',
    -1 => '🍓',
    1 => '🍋',
    'mango' => '🥭',
    'apple' => '🍎',
    'banana' => '🍌', ]);
$fruits2 = $fruits->concat(['🍊', '🍍']);
print_result($fruits2);

// Joins all elements into a string separated by separator
print_result($arr->join());

// Returns a section of arr from start to end
print_result($arr->slice(1, 2));

//Returns index of first occurrence of element in arr
print_result($arr->indexOf(5));

// Returns index of last occurrence of element in arr
$arr2 = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
print_result($arr->lastIndexOf(5));

// Calls function fn for each element in the array
$x = $arr->forEach(
    fn ($element, $key): int|float => $key * $element
);
print_result($x->arr());

// Returns true if all elements in arr pass the test in fn
$bool = $arr->every(fn ($element): bool => $element > 1);
print_result($bool);

// Returns true if at least one element in arr pass the test in fn
$bool = $arr->some(fn ($element, $key): bool => $element > 2);
print_result($bool);

// Returns new array with elements of arr passing the filtering function fn
$arr2 = $arr->filter(fn ($element): bool => $element > 3);
print_result($arr2);

// Returns new array with the results of running fn on every element
$arr2 = $arr->map(fn ($element): int|float => $element + 1);
print_result($arr2);

// Returns a flat array with sub-arrays concatenated
$arr = Arr::make([1, [2, 3], 4, [5, 6, 7]]);
$arr2 = $arr->flat();
print_result($arr2);

// Returns a Arr same as ->map() with a successive ->flat()
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
$arr2 = $arr->flatMap(fn ($element): array => [$element, $element * 2]);
print_result($arr2);

// Changes all elements in range to at the specified value
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9]);
$arr->fill(88, 2, 6);
print_result($arr);
// Fills array
$arr = Arr::make();
$arr->fill(99, 0, 5);
print_result($arr);

// Returns a single value which is the function's accumulated result L2R
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
$value = $arr->reduce(fn ($previousValue, $currentValue): float|int|array => $previousValue + $currentValue);
print_result($value);

// Returns a single value which is the function's accumulated result R2L
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
$value = $arr->reduceRight(fn ($previousValue, $currentValue): float|int|array => $previousValue + $currentValue);
print_result($value);

// extract only values
$fruits = Arr::make([
    7 => '🥝',
    -1 => '🍓',
    1 => '🍋',
    'mango' => '🥭',
    'apple' => '🍎',
    'banana' => '🍌',
    '🍊',
    '🍍', ]);
$onlyFruits = $fruits->values();
print_result($onlyFruits);

// Add element to start of arr and return new length
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
print_result($arr->unshift(0));
print_result($arr);

// Adds element to the end of arr and returns new length
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
print_result($arr->push(9999));
print_result($arr);

// Reverse order of arr
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
print_result($arr->reverse());

// Sort the elements of arr
$arr = Arr::make([6, 2, 4, 2, 1, 9, 7]);
print_result($arr->sort());

// Changes content of arr removing, replacing and adding elements
$months = Arr::make(['Jan', 'March', 'April', 'June']);
$months->splice(1, 0, 'Feb');
// ['Jan', 'Feb', 'March', 'April', 'June']
$months->splice(4, 1, 'May');
// ['Jan', 'Feb', 'March', 'April', 'May']

// Returns a string representing arr its elements (same as arr.join(','))
$arr = Arr::make(['Jan', 'Feb', 'March', 'April', 'May']);
print_result($arr->toString());

// Returns length of Arr
$arr = Arr::make(['Jan', 'Feb', 'March', 'April', 'May']);
print_result($arr->length());

// Returns true if arr is an array
$isArray = Arr::isArray(['Jan', 'Feb', 'March', 'April', 'May']);
print_result($isArray);

// Create Arr from a string
$arr = Arr::from('foo');
print_result($arr);

// Create Arr from array-like object
$arr = Arr::from([1, 2, 3], fn ($x): float|int|array => $x + $x);
print_result($arr);

// Create Arr from a function
$arr = Arr::fromFunction(fn (): int => random_int(0, 10), 5);
print_result($arr);

// Create Arr with a value
$arr = Arr::fromValue(0, 3);
print_result($arr);

$arr = Arr::fromValue(0, 3);
$arr[0] = 1001;
$arr[1] = 2002;
print_result($arr[1] + $arr[0]);
print_result($arr);

// Extract keys with keys()
$fruits = Arr::make([
    'kiwi' => '🥝',
    'fragola' => '🍓',
    'lemon' => '🍋',
    'mango' => '🥭',
    'apple' => '🍎',
    'banana' => '🍌', ]);
// keys as array
$arrayOfKeys = $fruits->keys();
print_result($arrayOfKeys);
$arrOfKeys = $fruits->keys(true);
print_result($arrOfKeys->join());

// get element by index, via at() method
$arr = Arr::make([1001, 1002, 1003, 1004, 1005, 1006]);
print_result($arr->at(1));
// 1002
print_result($arr->at(-2));
// 1005
print_result($arr->at(20000));
// NULL

// check element via includes() method
echo 'includes()'.PHP_EOL;
$arr = Arr::make([1, 2, 3]);
print_result($arr->includes(2));
print_result($arr->includes('2'));
print_result($arr->includes(3, 3));
print_result($arr->includes(3, 2));

// Extract entries with entries()
$fruits = Arr::make([
    'kiwi' => '🥝',
    'fragola' => '🍓',
    'lemon' => '🍋',
    'mango' => '🥭',
    'apple' => '🍎',
    'banana' => '🍌', ]);
// entries as array
$entries = $fruits->entries();
print_result($entries);

// Returns index of the first element in the array that satisfies the testing function
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
print_result($arr->findIndex(fn ($element): bool => $element > 0));
// 0
print_result($arr->findIndex(fn ($element): bool => $element > 1));
// 1
print_result($arr->findIndex(fn ($element): bool => $element > 10000));
// -1
print_result($arr->findIndex(fn ($element, $index): bool => $element > 1 && $index > 1));
// 2



// Returns the first element in the array that satisfies the testing function
$arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 8, 5, 14]);

print_result($arr->find(fn ($element): bool => $element > 5));
// 6
print_result($arr->find(fn ($element): bool => $element < 5));
// 1
print_result($arr->find(fn ($element): bool => $element > 10000));
// null
print_result($arr->find(fn ($element, $index): bool => $element > 1 && $index > 1));
// 3
$arr = Arr::make(['foo', 'bar', 'baz']);
print_result($arr->find(fn ($element): bool => str_contains((string) $element, 'a')));
// 'bar'

// Returns the shadow copied part of the array to another location and keeps its length
$arr = Arr::make([1, 2, 3, 4, 5]);
print_result($arr->copyWithin(-2));
// [1, 2, 3, 1, 2]
$arr = Arr::make([1, 2, 3, 4, 5]);
print_result($arr->copyWithin(0, 3));
// [4, 5, 3, 4, 5]
$arr = Arr::make([1, 2, 3, 4, 5]);
print_result($arr->copyWithin(0, 3, 4));
// [4, 2, 3, 4, 5]
$arr = Arr::make([1, 2, 3, 4, 5]);
print_result($arr->copyWithin(-2, -3, -1));
// [1, 2, 3, 3, 4]

// Ability to set and unset elements from the Arr
$arr = Arr::make(['mango' => '🥭', 'banana' => '🍌']);
$arr->set('apple', '🍎');
print_result($arr->arr());
// ['mango' => '🥭', 'banana' => '🍌', 'apple' => '🍎']
$arr->unset('banana');
print_result($arr->arr());
// ['mango' => '🥭', 'apple' => '🍎']




/**
 * Print a line for string, integer, array, boolean.
 */
function print_result(mixed $something): void
{
    switch (gettype($something)) {
        case 'string':
            echo 'STRING  : ' . $something . PHP_EOL;

            break;
        case 'integer':
            echo 'INTEGER : ' . $something . PHP_EOL;

            break;
        case 'array':
            echo 'ARRAY   : ' . implode(',', $something) . PHP_EOL;

            break;
        case 'boolean':
            echo 'BOOLEAN : ' . (($something) ? 'true' : 'false') . PHP_EOL;

            break;
        default:
            var_dump($something);

            break;
    }
}
