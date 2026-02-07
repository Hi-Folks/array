<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class CalcTest extends TestCase
{
    public function test_sum_array(): void
    {
        $arr = Arr::make([1, 2, 3]);
        $this->assertEquals(6, $arr->sum());

        $arr = Arr::make(['3', 2, 3]);
        $this->assertEquals(8, $arr->sum());
        $arr = Arr::fromValue(1, 100);
        $this->assertEquals(100, $arr->sum());
    }

    public function test_avg_array(): void
    {
        $arr = Arr::make([1, 2, 3]);
        $this->assertEquals(2, $arr->avg());

        $arr = Arr::fromValue(1, 100);
        $this->assertEquals(1, $arr->avg());

        $arr = Arr::fromValue(1.2, 100);
        $this->assertEquals(1.2, round($arr->avg(), 2));

        $arr = Arr::make([1.1, 1.5]);
        $this->assertEquals(1.3, $arr->avg());

        $arr = Arr::make([]);
        $this->assertEquals(0, $arr->avg());
    }
}
