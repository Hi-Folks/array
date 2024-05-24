<?php

declare(strict_types=1);

namespace HiFolks\DataType;

use ArrayAccess;
use Closure;
use Countable;
use HiFolks\DataType\Traits\Calculable;
use Iterator;
use Traversable;

/**
 * Class Arr
 * @package HiFolks\DataType
 *
 * @implements Iterator<int|string, mixed>
 * @implements ArrayAccess<int|string, mixed>
 */
final class Arr implements Iterator, ArrayAccess, Countable
{
    use Calculable;

    /** @var array<int|string, mixed> */
    private array $arr;

    /** @param array<int|string, mixed> $arr */
    public function __construct(array $arr = [])
    {
        $this->arr = $arr;
    }

    public static function fromFunction(callable $callable, int $count): Arr
    {
        $array = [];
        for ($i = 0; $i < $count; $i++) {
            $array[$i] = $callable($i);
        }

        return self::make($array);
    }

    public static function fromValue(mixed $value, int $count): self
    {
        return self::make(array_fill(0, $count, $value));
    }

    /**
     * Creates a new Arr instance from a string or array-like object.
     *
     * @param iterable<int|string, mixed>|string $arrayLike
     */
    public static function from(iterable|string $arrayLike, ?Closure $mapFn = null): self
    {
        if (is_string($arrayLike)) {
            $arrayLike = str_split($arrayLike);
        }

        if ($arrayLike instanceof Traversable) {
            $arrayLike = iterator_to_array($arrayLike);
        }

        $array = self::make($arrayLike);

        if (! is_callable($mapFn)) {
            return $array;
        }

        return $array->map($mapFn);
    }

    /** @param array<int|string, mixed> $arr */
    public static function make(array $arr = []): self
    {
        return new self($arr);
    }

    /**
     * Creates a new Arr instance from a variable number of arguments,
     * regardless of number or type of the arguments.
     *
     * @return Arr object
     */
    public static function of(mixed ...$elements): self
    {
        return self::make($elements);
    }

    public function count(): int
    {
        return count($this->arr);
    }

    public function length(): int
    {
        return $this->count();
    }

    /**
     * Get the array
     * @return array<int|string, mixed>
     */
    public function arr(): array
    {
        return $this->arr;
    }

    /**
     * Get the element with $index
     * @param non-empty-string $charNestedKey
     */
    public function get(mixed $index, mixed $defaultValue = null, string $charNestedKey = "."): mixed
    {
        if (is_string($index)) {
            $indexString = strval($index);
            if (str_contains($indexString, $charNestedKey)) {
                $nestedValue = $this->arr;
                foreach (explode($charNestedKey, $indexString) as $nestedKey) {
                    if (is_array($nestedValue) && array_key_exists($nestedKey, $nestedValue)) {
                        $nestedValue = $nestedValue[$nestedKey];
                    } else {
                        return $defaultValue;
                    }
                }
                return $nestedValue;
            }
        }
        return $this->arr[$index] ?? $defaultValue;
    }

    /**
     * Set a value to a specific key
     */
    public function set(int|string $key, mixed $value): void
    {
        $this->arr[$key] = $value;
    }

    /**
     * Unset an array element by their key if it exists
     */
    public function unset(mixed $index): bool
    {
        if ($this->get($index)) {
            unset($this->arr[$index]);
            return true;
        }

        return false;
    }

    /**
     * Takes an integer value $index and returns the item at that index,
     * allowing for positive and negative integers.
     * Negative integers count back from the last item in the array.
     * For lists, map, associative array, uses get() method.
     *
     * @param  int  $index the index of the element of the array  (negative cont back from last item
     * @return mixed item value (null if $index is not existent
     */
    public function at(int $index): mixed
    {
        if ($index < 0) {
            $index = $this->length() + $index;
        }

        return $this->get($index);
    }

    /**
     * Return the current element
     *
     * @link https://php.net/manual/en/iterator.current.php
     *
     * @return mixed Can return any type.
     */
    public function current(): mixed
    {
        return current($this->arr);
    }

    /**
     * Move forward to next element
     *
     * @link https://php.net/manual/en/iterator.next.php
     *
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        next($this->arr);
    }

    public function prev(): mixed
    {
        return prev($this->arr);
    }

    /**
     * Return the key of the current element
     *
     * @link https://php.net/manual/en/iterator.key.php
     *
     * @return string|int|null scalar on success, or null on failure.
     */
    public function key(): string|int|null
    {
        return key($this->arr);
    }

    /**
     * Checks if current position is valid
     *
     * @link https://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return ! is_null($this->key());
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link https://php.net/manual/en/iterator.rewind.php
     *
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        reset($this->arr);
    }

    /**
     * It executes a provided function ($callback) once for each element.
     */
    public function forEach(callable $callback): self
    {
        $x = array_map($callback, $this->arr, array_keys($this->arr));

        return self::make($x);
    }

    /**
     * Returns a new array [] or a new Arr object that contains the keys
     * for each index in the Arr object
     * It returns Arr or [] depending on $returnArrClass value
     *
     * @param bool $returnArrClass true if you need Arr object
     * @return int|string|array<int|string, mixed>|Arr
     */
    public function keys(bool $returnArrClass = false): int|string|array|Arr
    {
        if ($returnArrClass) {
            return self::make(array_keys($this->arr));
        }

        return array_keys($this->arr);
    }

    /**
     * Add an $element to the end of an array and returns new length of the array.
     */
    public function push(mixed ...$element): int
    {
        return array_push($this->arr, ...$element);
    }

    /**
     * It removes the last element from an array and returns that element.
     * This method changes the length of the Arr
     *
     * @return mixed the element removed
     */
    public function pop(): mixed
    {
        return array_pop($this->arr);
    }

    /**
     * Add element to start of Arr and return new length
     *
     * @return int the new length of the array upon which the method was called.
     */
    public function unshift(mixed ...$element): int
    {
        return array_unshift($this->arr, ...$element);
    }

    /**
     * Removes the first element from an array and
     * returns that removed element.
     * This method changes the length of the array.
     *
     * @return mixed the removed element
     */
    public function shift(): mixed
    {
        return array_shift($this->arr);
    }

    /**
     * Append arrays into the current one
     * @param array<int|string, mixed> $elements
     */
    public function append(...$elements): self
    {
        $this->arr = array_merge($this->arr, ...$elements);

        return $this;
    }

    /**
     * Returns new Arr joining more elements: array, Arr, scalar type.
     * This method does not change the existing Arr object,
     * but instead returns a new Arr object
     *
     */
    public function concat(mixed ...$elements): Arr
    {
        $array = $this->arr;
        foreach ($elements as $element) {
            switch (gettype($element)) {
                case 'array':
                    /** @var array<int|string, mixed> $element */
                    $array = array_merge($array, $element);

                    break;
                case 'string':
                case 'integer':
                case 'boolean':
                case 'double':
                    $array = array_merge($array, [$element]);

                    break;
                case 'object':
                    if ($element instanceof Arr) {
                        $array = array_merge($array, $element->arr());
                    }

                    break;
            }
        }

        return self::make($array);
    }

    /**
     * Joins all elements into a string, separated by $separator
     *
     * @param  string  $separator the separator, could be also a string with more than 1 char
     */
    public function join(string $separator = ','): string
    {
        return implode($separator, $this->arr);
    }

    /**
     * Returns as new Arr instance of a portion of an array into a new Arr object
     * selected from $start to $end ($end not included)
     * where start and end represent the index of items in that array.
     * The original array will not be modified
     *
     * @param int $start start index (array start from 0, start included)
     * @param int|null $end end index (array starts from 0, end not included)
     */
    public function slice(int $start, int $end = null): Arr
    {
        if (is_null($end)) {
            $end = $this->length();
        }
        if ($end < 0) {
            $end = $this->length() + $end;
        }
        if ($start < 0) {
            $start = $this->length() + $start;
        }

        return self::make(array_slice($this->arr, $start, $end - $start));
    }

    /**
     * Returns index of first occurrence of element in arr
     *
     * @return string|int|bool the index of element found. If it is not found, false is returned
     */
    public function indexOf(mixed $searchElement): string|int|bool
    {
        return array_search($searchElement, $this->arr, true);
    }

    /**
     * Returns index of last occurrence of element in Arr
     *
     * @return string|int|bool the index of element found. If it is not found, false is returned
     */
    public function lastIndexOf(mixed $searchElement): string|int|bool
    {
        return array_search($searchElement, array_reverse($this->arr, true), true);
    }

    /**
     * Returns true if all elements in Arr pass the test in fn.
     */
    public function every(callable $callback): bool
    {
        foreach ($this->arr as $key => $element) {
            if (! $callback($element, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if all elements in arr pass the test in fn.
     * Tests whether at least one element in the array passes the test
     * implemented by the provided function.
     * It returns true if, in the array, it finds an element for which
     * the provided function returns true;
     * otherwise it returns false.
     * It doesn't modify the array.
     */
    public function some(callable $callback): bool
    {
        foreach ($this->arr as $key => $element) {
            if ($callback($element, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines whether the array includes a certain value $element among its entries,
     * returning true or false as appropriate
     *
     * @param  int|null  $fromIndex
     */
    public function includes(mixed $element, int $fromIndex = null): bool
    {
        if (is_null($fromIndex)) {
            return in_array($element, $this->arr, true);
        }

        return in_array($element, array_slice($this->arr, $fromIndex), true);
    }

    /**
     * Returns new Arr with elements of arr passing the filtering function fn
     *
     * @param  callable  $callback the function for filtering
     */
    public function filter(callable $callback): Arr
    {
        return self::make(array_filter($this->arr, $callback));
    }

    /**
     * Returns index of the first element in arr passing the callback function
     *
     * @param  callable  $callback the function for finding the index
     */
    public function findIndex(callable $callback): int|string
    {
        foreach ($this->arr as $key => $element) {
            if ($callback($element, $key, $this->arr)) {
                return $key;
            }
        }

        return -1;
    }

    /**
     * Returns a new Arr populated with the results of
     * calling a provided function on every element in the calling array
     */
    public function map(callable $callback): Arr
    {
        return $this->forEach($callback);
    }

    /**
     * Returns a new Arr object as flatten array with subarrays concatenated
     */
    public function flat(): self
    {
        $array = array_reduce(
            $this->arr,
            fn ($result, $element): array => array_merge($result, is_array($element) ? [...$element] : [$element]),
            []
        );

        return self::make($array);
    }

    /**
     * The flatMap method is identical to a map followed by a call to flat of depth 1
     */
    public function flatMap(callable $callback): self
    {
        $array = Arr::make([]);

        foreach ($this->arr as $key => $element) {
            if (is_array($element)) {
                $array->push(...self::make($element)
                    ->forEach($callback)
                    ->arr());
                continue;
            }

            $a = $callback($element, $key);

            if (is_array($a)) {
                $array->push(...$a);
            } else {
                $array->push($a);
            }
        }

        return $array;
    }

    /**
     * Changes all elements in range from $start for $count, to the specified value
     *
     * @param  int  $start start index (from 0)
     * @param  int|null  $end end index (default, the end of array)
     */
    public function fill(mixed $value, int $start = 0, int $end = null): void
    {
        if (is_null($end)) {
            $end = $this->length() - 1;
        }
        for ($i = $start; $i <= $end; $i++) {
            $this->arr[$i] = $value;
        }
    }

    /**
     * Executes a user-supplied $callback callback function on each element of the array,
     * in order, passing in the return value from the calculation on the preceding element.
     * The final result of running the reducer across all elements of the array
     * is a single value.
     *
     * @return mixed the result
     */
    public function reduce(callable $callback, mixed $initialValue = 0): mixed
    {
        return array_reduce($this->arr, $callback, $initialValue);
    }

    /**
     * Applies a function against an accumulator and each value of the array
     * (from right-to-left) to reduce it to a single value.
     */
    public function reduceRight(callable $callback, mixed $initialValue = 0): mixed
    {
        return array_reduce(array_reverse($this->arr), $callback, $initialValue);
    }

    /**
     * Reverses an array in place, and it returns also a new instance of Arr
     * The first array element becomes the last,
     * and the last array element becomes the first.
     *
     * @param  bool  $preserve_keys if set to true keys are preserved
     */
    public function reverse(bool $preserve_keys = false): Arr
    {
        $this->arr = array_reverse($this->arr, $preserve_keys);

        return self::make($this->arr);
    }

    /**
     * Sorts the elements of an Arr object in place and returns the sorted Arr object.
     * The default sort order is ascending
     */
    public function sort(): Arr
    {
        sort($this->arr);

        return $this;
    }

    /**
     * Changes content of arr removing, replacing and adding elements
     * Changes the contents of an array by removing or replacing existing elements
     * and/or adding new elements in place.
     * To access part of an array without modifying it, see slice().
     *
     * @param  int  $start the index at which to start changing the array
     * @param  int|null  $deleteCount an integer indicating the number of elements in the array to remove from $start
     * @return Arr an array containing the deleted elements
     */
    public function splice(int $start, int $deleteCount = null, mixed $newElements = []): Arr
    {
        return self::make(array_splice($this->arr, $start, $deleteCount, $newElements));
    }

    /**
     * Returns a string representing arr its elements (same as arr.join(','))
     */
    public function toString(): string
    {
        return $this->join();
    }

    /**
     * Returns true if the input is an array
     *
     * @return bool true if $input is an array, false otherwise
     */
    public static function isArray(mixed $input): bool
    {
        return is_array($input);
    }

    /**
     * This method is executed when using isset() or empty()
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->arr);
    }

    /**
     * Offset to retrieve
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->arr[] = $value;
        } else {
            $this->arr[$offset] = $value;
        }
    }

    /**
     * This method will not be called when type-casting to (unset)
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->arr[$offset]);
    }

    /**
     * It creates a new Arr object with the values of the current one (keys are skipped)
     *
     * @return Arr an array containing only the values (not keys)
     */
    public function values(): self
    {
        return new self(array_values($this->arr));
    }

    /**
     * @return Arr object that contains the key/value pairs for each index in the array
     */
    public function entries(): self
    {
        $pairs = [];
        foreach ($this->arr as $k => $v) {
            $pairs[] = [$k, $v];
        }

        return self::make($pairs);
    }

    /**
     * @param Callable $callback the function to find the matching element
     * @return mixed the first element in the array that satisfies
     * the provided function
     */
    public function find(callable $callback): mixed
    {
        foreach ($this->arr as $key => $element) {
            if ($callback($element, $key, $this->arr)) {
                return $element;
            }
        }

        return null;
    }

    /**
     * The copyWithin() method shallow copies part of an array to another
     * location in the same array and returns it without modifying its length.
     *
     * @param int|null $end
     * @return array<int|string, mixed>
     */
    public function copyWithin(int $target, int $start = 0, int $end = null): array
    {
        $arrayLength = $this->length();
        $chuck = $this->slice($start, $end);
        if ($target < 0) {
            $target = $arrayLength - (int) abs($target);
        }

        foreach ($chuck as $value) {
            $this->arr[$target] = $value;
            $target++;
        }

        $this->arr = $this->slice(0, $arrayLength)->arr;
        return $this->arr;
    }

    /**
     * Returns true if the input array is empty
     * @return bool true if $input is an empty array, false otherwise
     */
    public function isEmpty(): bool
    {
        return $this->length() === 0;
    }
}
