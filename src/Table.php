<?php

declare(strict_types=1);

namespace HiFolks\DataType;

use Countable;
use HiFolks\DataType\Enums\GroupByKeyMode;
use Iterator;

/**
 * @implements Iterator<int|string, Arr|Table>
 */
final class Table implements Countable, Iterator
{
    /**
     * @var array<int|string, Arr|Table> $rows
     */
    private array $rows = [];

    /**
     * @param array<int|string, array<int|string, mixed>|Arr|Table> $array
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
     * @return array<int|string, Arr|Table>
     */
    public function rows(): array
    {
        return $this->rows;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->rows as $key => $row) {
            $result[$key] =
                $row instanceof Table ? $row->toArray() : $row->arr();
        }
        return $result;
    }

    /**
     * @param array<int|string, mixed>|Arr|Table $arr
     * @return $this
     */
    public function append(array|Arr|Table $arr): self
    {
        $this->rows[] = $arr instanceof Table ? $arr : $this->getArr($arr);
        return $this;
    }

    public function first(): Arr|Table|null
    {
        $array = array_reverse($this->rows);
        return array_pop($array);
    }

    public function last(): Arr|Table|null
    {
        $reference = $this->rows;
        return array_pop($reference);
    }

    public function get(int|string $key): Arr|Table|null
    {
        return $this->rows[$key] ?? null;
    }

    public function getFromFirst(int|string $field): mixed
    {
        $first = $this->first();
        if (!$first instanceof Arr) {
            return null;
        }
        return $first->get($field);
    }

    public function getFromLast(int|string $field): mixed
    {
        $last = $this->last();
        if (!$last instanceof Arr) {
            return null;
        }
        return $last->get($field);
    }

    public function select(int|string ...$columns): self
    {
        $table = self::make([]);
        foreach ($this->rows as $row) {
            if (!$row instanceof Arr) {
                continue;
            }
            $newRow = [];
            foreach ($columns as $column) {
                $value = $row->get($column);
                $newRow[$column] = $value;
            }
            $table->append(Arr::make($newRow));
        }

        return $table;
    }

    /**
     * It returns a new Table instance with data, excluding the attributes listed in
     * $columns
     */
    public function except(int|string ...$columns): self
    {
        $table = self::make([]);
        foreach ($this->rows as $row) {
            if (!$row instanceof Arr) {
                continue;
            }
            $newRow = $row;
            foreach ($columns as $column) {
                $newRow->unset($column);
            }
            $table->append($newRow);
        }

        return $table;
    }

    public function where(
        string|int $field,
        mixed $operator = null,
        mixed $value = null,
    ): self {
        if (func_num_args() === 1) {
            $value = true;
            $operator = "==";
        }
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = "===";
        }

        $function = match ($operator) {
            "==" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) == $value,
            ">" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) > $value,
            "<" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) < $value,
            ">=" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) >= $value,
            "<=" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) <= $value,
            "!=" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) != $value,
            "!==" => fn (Arr|Table $element): bool => $element instanceof Arr &&
                $element->get($field) !== $value,
            default => fn (Arr|Table $element): bool => $element instanceof
                Arr && $element->get($field) === $value,
        };
        $filteredArray = array_filter($this->rows, $function);

        return self::make($filteredArray);
    }

    public function orderBy(string|int $field, string $order = "desc"): self
    {
        /** @var array<int|string, Arr> $array */
        $array = array_filter(
            $this->rows,
            static fn (Arr|Table $row): bool => $row instanceof Arr,
        );

        if ($order !== "asc") {
            $closure = static fn (Arr $item1, Arr $item2): int => $item2->get(
                $field,
            ) <=> $item1->get($field);
        } else {
            $closure = static fn (Arr $item1, Arr $item2): int => $item1->get(
                $field,
            ) <=> $item2->get($field);
        }

        usort($array, $closure);
        return self::make($array);
    }

    /**
     * @return $this
     */
    public function calc(string|int $destinationField, callable $function): self
    {
        foreach ($this->rows as $value) {
            if (!$value instanceof Arr) {
                continue;
            }
            $value[$destinationField] = $function($value);
        }

        return $this;
    }

    /**
     * Groups rows by a field value, returning a Table of Tables.
     * Each key is derived from the field value using the specified key mode,
     * and each value is a Table containing all rows with that field value.
     */
    public function groupBy(
        string|int $field,
        GroupByKeyMode $keyMode = GroupByKeyMode::VarExport,
    ): Table {
        /** @var array<int|string, list<Arr>> $groups */
        $groups = [];

        foreach ($this->rows as $value) {
            if (!$value instanceof Arr) {
                continue;
            }
            $property = $value->get($field);
            $property = $this->castVariableForStrval($property);
            if ($property === null) {
                continue;
            }
            if (is_int($property) || is_string($property)) {
                $lookupKey = $property;
            } else {
                $lookupKey = match ($keyMode) {
                    GroupByKeyMode::VarExport => var_export($property, true),
                    GroupByKeyMode::Strval => strval($property),
                    GroupByKeyMode::Int => intval($property),
                };
            }
            $groups[$lookupKey][] = $value;
        }

        /** @var array<int|string, Table> $tableGroups */
        $tableGroups = [];
        foreach ($groups as $key => $rows) {
            $tableGroups[$key] = self::make($rows);
        }

        return $this->makeFromGroups($tableGroups);
    }

    /**
     * @param array<int|string, Table> $groups
     */
    private function makeFromGroups(array $groups): self
    {
        $table = new self();
        foreach ($groups as $key => $subTable) {
            $table->rows[$key] = $subTable;
        }
        return $table;
    }

    /**
     * strval sometimes crashes when passed a mixed value, this fixes that problem.
     */
    private function castVariableForStrval(
        mixed $property,
    ): bool|float|int|string|null {
        return match (gettype($property)) {
            "boolean" => $property,
            "double" => $property,
            "integer" => $property,
            "string" => $property,
            default => null,
        };
    }

    /**
     * Transform allows you to run a function over an entire table on a specific row
     */
    public function transform(string|int $field, callable $function): self
    {
        $array = $this->rows;

        foreach ($array as $row) {
            if (!$row instanceof Arr) {
                continue;
            }
            /** @var array<int, mixed> $keys */
            $keys = $row->keys();
            if (in_array($field, $keys, true)) {
                $result = $function($row->get($field));
                $row->set($field, $result);
            }
        }

        return self::make($array);
    }

    public function toJson(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->toArray(), $options | JSON_THROW_ON_ERROR);
    }

    public function count(): int
    {
        return count($this->rows);
    }

    /**
     * @param array<int|string, mixed>|Arr $value
     */
    private function getArr(array|Arr $value): Arr
    {
        if (!$value instanceof Arr) {
            return Arr::make($value);
        }
        return $value;
    }

    public function current(): Arr|Table|false
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
        return !is_null($this->key());
    }

    public function rewind(): void
    {
        reset($this->rows);
    }
}
