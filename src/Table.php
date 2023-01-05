<?php

declare(strict_types=1);

namespace HiFolks\DataType;

use Countable;
use Iterator;

/**
 * @implements Iterator<int|string, Arr>
 */
final class Table implements Countable, Iterator
{
    /**
     * @var array<int|string, Arr> $rows
     */
    private array $rows = [];

    /**
     * @param array<int|string, array<int|string, mixed>|Arr> $array
     * @return Table
     */
    public static function make(array $array): self
    {
        $table = new self();
        foreach ($array as $value) {
            $table->append($value);
        }

        return $table;
    }

    /**
     * @return Arr[]
     */
    public function rows(): array
    {
        return $this->rows;
    }

    /**
     * @return array<int|string, array<int|string, mixed>>
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->rows() as $key => $row) {
            $result[$key] = $row->arr();
        }
        return $result;
    }

    /**
     * @param array<int|string, mixed>|Arr $arr
     * @return $this
     */
    public function append(array|Arr $arr): self
    {
        $this->rows[] = $this->getArr($arr);
        return $this;
    }

    /**
     * @return Arr|null
     */
    public function first(): ?Arr
    {
        $array = array_reverse($this->rows);
        return array_pop($array);
    }

    /**
     * @return Arr|null
     */
    public function last(): ?Arr
    {
        $reference = $this->rows;
        return array_pop($reference);
    }

    /**
     * @param int|string $field
     * @return mixed
     */
    public function getFromFirst(int|string $field): mixed
    {
        return $this->first()?->get($field);
    }

    /**
     * @param int|string $field
     * @return mixed
     */
    public function getFromLast(int|string $field): mixed
    {
        return $this->last()?->get($field);
    }

    /**
     * @param int|string ...$columns
     * @return self
     */
    public function select(int|string ...$columns): self
    {
        $table = self::make([]);
        foreach ($this->rows as $row) {
            $newRow = [];
            foreach ($columns as $column) {
                $value = $row->get($column);
                if ($column !== null) {
                    $newRow[$column] = $value;
                }
            }
            $table->append(Arr::make($newRow));
        }

        return $table;
    }

    /**
     * It returns a new Table instance with data, excluding the attributes listed in
     * $columns
     *
     * @param  int|string ...$columns
     * @return self
     */
    public function except(int|string ...$columns): self
    {
        $table = self::make([]);
        foreach ($this->rows as $row) {
            $newRow = $row;
            foreach ($columns as $column) {
                $newRow->unset($column);
            }
            $table->append($newRow);
        }

        return $table;
    }

    /**
     * @param  string|int  $field
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return self
     */
    public function where(string|int $field, mixed $operator = null, mixed $value = null): self
    {
        if (func_num_args() === 1) {
            $value = true;
            $operator = '==';
        }
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '===';
        }

        $function = match ($operator) {
            '==' => fn ($element) => $element->get($field) == $value,
            '>' => fn ($element) => $element->get($field) > $value,
            '<' => fn ($element) => $element->get($field) < $value,
            '>=' => fn ($element) => $element->get($field) >= $value,
            '<=' => fn ($element) => $element->get($field) <= $value,
            '!=' => fn ($element) => $element->get($field) != $value,
            '!==' => fn ($element) => $element->get($field) !== $value,
            default => fn ($element) => $element->get($field) === $value
        };
        $filteredArray = array_filter($this->rows, $function);

        return self::make($filteredArray);
    }

    /**
     * @param string|int $field
     * @param string $order
     * @return Table
     */
    public function orderBy(string|int $field, string $order = 'desc'): self
    {
        $array = $this->rows();

        if ($order !== 'asc') {
            $closure = static function (Arr $item1, Arr $item2) use ($field) {
                return $item2->get($field) <=> $item1->get($field);
            };
        } else {
            $closure = static function ($item1, $item2) use ($field) {
                return $item1->get($field) <=> $item2->get($field);
            };
        }

        usort($array, $closure);
        return self::make($array);
    }

    /**
     * @param  string|int  $destinationField
     * @param  callable  $function
     * @return $this
     */
    public function calc(string|int $destinationField, callable $function): self
    {
        foreach ($this->rows as $key => $value) {
            $this->rows[$key][$destinationField] = $function($value);
        }

        return $this;
    }

    /**
     * This will only return the values for the first time it a unique row within that group
     * @param string|int $field
     * @return Table
     */
    public function groupBy(string|int $field): Table
    {
        $result = [];

        foreach ($this->rows as $value) {
            $property = $value->get($field);
            if (array_key_exists(strval($property), $result)) {
                continue;
            }
            $result[$property] = $value;
        }

        return self::make(array_values($result));
    }

    /**
     * Transform allows you to run a function over an entire table on a specific row
     */
    public function transform(
        string|int $field,
        callable $function,
    ): self {
        $array = $this->rows();

        foreach ($array as $row) {
            /** @var array<int, mixed> $keys */
            $keys = $row->keys();
            if (in_array($field, $keys, true)) {
                $result = $function($row->get($field));
                $row->set($field, $result);
            }
        }

        return self::make($array);
    }

    public function count(): int
    {
        return count($this->rows());
    }

    /**
     * @param array<int|string, mixed>|Arr $value
     * @return Arr
     */
    private function getArr(array|Arr $value): Arr
    {
        if (!$value instanceof Arr) {
            $value = Arr::make($value);
        }
        return $value;
    }

    public function current(): Arr|false
    {
        return current($this->rows);
    }

    public function next(): void
    {
        next($this->rows);
    }

    public function prev(): void
    {
        prev($this->rows);
    }

    public function key(): string|int|null
    {
        return key($this->rows);
    }

    public function valid(): bool
    {
        return ! is_null($this->key());
    }

    public function rewind(): void
    {
        reset($this->rows);
    }
}
