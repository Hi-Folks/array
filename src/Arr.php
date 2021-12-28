<?php

namespace HiFolks\DataType;

use HiFolks\DataType\Traits\Calculable;

class Arr implements \Iterator, \ArrayAccess
{
    use Calculable;

    private array $arr;
    private int $idx;

    public function __construct(array $arr = [])
    {
        $this->arr = $arr;
    }

    public static function fromFunction($callable, $count)
    {
        $array = [];
        for ($i = 0; $i < $count; $i++) {
            $array[$i] = $callable($i);
        }

        return self::make($array);
    }

    /**
     * @param $value
     * @param $count
     * @return static
     */
    public static function fromValue(mixed $value, int $count): self
    {
        return self::make(array_fill(0, $count, $value));
    }

    public static function make(array $arr = []): self
    {
        return new self($arr);
    }

    /**
     * Creates a new Arr instance from a variable number of arguments,
     * regardless of number or type of the arguments.
     * @param ...$elements
     * @return Arr object
     */
    public static function of(...$elements): self
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
     */
    public function arr(): array
    {
        return $this->arr;
    }

    /**
     * Get the element with $index
     */
    public function get(mixed $index): mixed
    {
        return $this->arr[$index] ?? null;
    }

    /**
     * Takes an integer value $index and returns the item at that index,
     * allowing for positive and negative integers.
     * Negative integers count back from the last item in the array.
     * For lists, map, associative array, uses get() method.
     * @param int $index the index of the element of the array  (negative cont back from last item
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
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current(): mixed
    {
        return current($this->arr);
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        next($this->arr);
    }

    public function prev()
    {
        return prev($this->arr);
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return string|float|int|bool|null scalar on success, or null on failure.
     */
    public function key(): mixed
    {
        return key($this->arr);
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return ! is_null($this->key());
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        reset($this->arr);
    }

    public function forEach($callback)
    {
        $x = array_map($callback, $this->arr, array_keys($this->arr));

        return self::make($x);
    }

    /**
     * Returns a new array [] or a new Arr object that contains the keys
     * for each index in the Arr object
     * It returns Arr or [] depending on $returnArrClass value
     * @param bool $returnArrClass true if you need Arr object
     * @return int[]|string[]|Arr
     */
    public function keys($returnArrClass = false): array|Arr
    {
        if ($returnArrClass) {
            return Arr::make(array_keys($this->arr));
        }

        return array_keys($this->arr);
    }

    /**
     * Add an $element to the end of an array and returns new length
     */
    public function push($element): int
    {
        return array_push($this->arr, $element);
    }

    /**
     * Remove an item from the end of an Array
     */
    public function pop(): mixed
    {
        return array_pop($this->arr);
    }

    /**
     * Add element to start of Arr and return new length
     */
    public function unshift($element): int
    {
        return array_unshift($this->arr, $element);
    }

    /**
     * Remove an item from the beginning of an Array
     */
    public function shift(): mixed
    {
        return array_shift($this->arr);
    }

    /**
     * Append arrays into the current one
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
     * @param ...$elements
     * @return Arr object
     */
    public function concat(mixed ...$elements): Arr
    {
        $array = $this->arr;
        foreach ($elements as $element) {
            switch (gettype($element)) {
                case "array":
                    $array = array_merge($array, $element);

                    break;
                case "string":
                case "integer":
                case "boolean":
                case "double":
                    $array = array_merge($array, [$element]);

                    break;
                case "object":
                    if (get_class($element) === get_class($this)) {
                        $array = array_merge($array, $element->arr());
                    }

                    break;
            }
        }

        return Arr::make($array);
    }

    /**
     * Joins all elements into a string, separated by $separator
     */
    public function join(string $separator = ","): string
    {
        return implode($separator, $this->arr);
    }

    /**
     * Returns a section of Arr from $start to $end
     */
    public function slice($start, $end): Arr
    {
        return Arr::make(array_slice($this->arr, $start, $end));
    }

    /**
     * Returns index of first occurrence of element in arr
     */
    public function indexOf($searchElement): string|int|bool
    {
        return array_search($searchElement, $this->arr);
    }

    /**
     * Returns index of last occurence of element in arr
     */
    public function lastIndexOf($searchElement): string|int|bool
    {
        return array_search($searchElement, array_reverse($this->arr, true));
    }

    /**
     * Returns true if all elements in Arr pass the test in fn.
     * @param $callback
     * @return bool
     */
    public function every($callback)
    {
        foreach ($this->arr as $key => $element) {
            if (! $callback($element, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if all elements in arr pass the test in fn
     */
    public function some($callback)
    {
        foreach ($this->arr as $key => $element) {
            if ($callback($element, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns new Arr with elements of arr passing the filtering function fn
     */
    public function filter($callback): Arr
    {
        return Arr::make(array_filter($this->arr, $callback));
    }

    /**
     * Returns new array with the results of running fn on every element
     */
    public function map($callback)
    {
        return $this->forEach($callback);
    }

    /**
     * Returns a flatten array with subarrays concatenated
     */
    public function flat()
    {
        $array = array_reduce(
            $this->arr,
            fn ($result, $element) =>
                array_merge($result, is_array($element) ? [...$element] : [$element]),
            []
        );

        return Arr::make($array);
    }

    /**
     * The flatMap method is identical to a map followed by a call to flat of depth 1
     */
    public function flatMap($callback)
    {
        $array = [];
        foreach ($this->arr as $key => $element) {
            $a = $callback($element, $key);
            if (is_array($a)) {
                $array = array_merge($array, [...$a]);
            } else {
                $array[] = $a;
            }
        }

        return Arr::make($array);
    }

    /**
     * Changes all elements in range from $start for $count, to the specified value
     * @param mixed $value the value to fill the array with
     * @param int $start start index (from 0)
     * @param int|null $end end index (default, the end of array)
     * @return void
     */
    public function fill(mixed $value, int $start = 0, int $end = null)
    {
        if (is_null($end)) {
            $end = $this->length() - 1;
        }
        for ($i = $start; $i <= $end; $i++) {
            $this->arr[$i] = $value;
        }
    }

    /**
     * Returns a single value which is the function's accumulated result L2R
     */
    public function reduce($callback, $initialValue = 0): mixed
    {
        return array_reduce($this->arr, $callback, $initialValue);
    }

    /**
     * Returns a single value which is the function's accumulated result R2L
     */
    public function reduceRight($callback, $initial = 0): mixed
    {
        return array_reduce(array_reverse($this->arr), $callback, $initial);
    }

    /**
     * Reverse order of arr
     */
    public function reverse($preserve_keys = false): Arr
    {
        $this->arr = array_reverse($this->arr, $preserve_keys);

        return Arr::make($this->arr);
    }

    /**
     * Sort the elements of arr
     */
    public function sort(): Arr
    {
        sort($this->arr);

        return $this;
    }

    /**
     * Changes content of arr removing, replacing and adding elements
     */
    public function splice($start, $deleteCount = null, $newElements = []): Arr
    {
        return Arr::make(array_splice($this->arr, $start, $deleteCount, $newElements));
    }

    /**
     * Returns a string representing arr its elements (same as arr.join(','))
     */
    public function toString(): string
    {
        return $this->join(',');
    }

    /**
     * Returns true if the input is an array
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
}
