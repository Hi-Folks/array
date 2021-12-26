<?php

namespace HiFolks\DataType\Traits;

trait Calculable
{
    /**
     * Returns the sum of values as an integer or float; 0 if the array is empty.
     * @return int|float
     */
    public function sum(): int|float
    {
        return array_sum($this->arr);
    }

    /**
     * Returns the average of values as an integer of float; 0 if the array is empty
     * @return int|float
     */
    public function avg(): int|float
    {
        if ($this->length() === 0) {
            return 0;
        }

        return $this->sum() / $this->length();
    }
}
