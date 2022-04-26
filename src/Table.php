<?php

namespace HiFolks\DataType;

class Table extends Arr
{
    private $schema = [

    ];

    public static function make(array $arr = []): self
    {
        return new self($arr);
    }

    public function insert(array $row)
    {
        $this->append($row);
    }

    public function first()
    {
        $firstKey = array_key_first($this->arr);

        return $this->get($firstKey);
    }

    public function last()
    {
        $lastKey = array_key_last($this->arr);

        return $this->get($lastKey);
    }

    public function getFromFirst(int|string $field)
    {
        $firstRow = $this->first();

        return $firstRow[$field];
    }

    public function getFromLast(int|string $field)
    {
        $lastRow = $this->last();

        return $lastRow[$field];
    }

    /**
     * @param array $columns
     * @return self
     */
    public function select(array $columns): self
    {
        $filteredArray = array_map(fn ($item) => array_intersect_key($item, array_flip($columns)), $this->arr);

        return new self($filteredArray);
    }

    /**
     * It returns a new Table instance with data, excluding the attributes listed in
     * $columns
     * @param array $columns
     * @return self
     */
    public function except(array $columns): self
    {
        $filteredArray = array_map(fn ($item) => array_diff_key($item, array_flip($columns)), $this->arr);

        return new self($filteredArray);
    }

    /**
     * @param string|int $field
     * @param mixed $operator
     * @param mixed $value
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
            "==" => fn ($element) => $element[$field] == $value,
            "===" => fn ($element) => $element[$field] === $value,
            ">" => fn ($element) => $element[$field] > $value,
            "<" => fn ($element) => $element[$field] < $value,
            ">=" => fn ($element) => $element[$field] >= $value,
            "<=" => fn ($element) => $element[$field] <= $value,
            "!=" => fn ($element) => $element[$field] != $value,
            "!==" => fn ($element) => $element[$field] !== $value,
            default => fn ($element) => $element[$field] == $value
        };
        $filteredArray = array_filter($this->arr, $function);

        return new self($filteredArray);
    }

    /**
     * @param string|int $destinationField
     * @param callable $function
     * @return $this
     */
    public function calc(string|int $destinationField, callable $function): self
    {
        foreach ($this->arr as $key => $value) {
            $this->arr[$key][$destinationField] = $function($value);
        }

        return $this;
    }
}
