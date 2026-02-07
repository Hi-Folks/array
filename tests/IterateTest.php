<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class IterateTest extends TestCase
{
    public function test_iterates(): void
    {
        $arr = Arr::make([100, 101, 102]);
        foreach ($arr as $element) {
            $this->assertGreaterThanOrEqual(100, $element);
            $this->assertLessThanOrEqual(102, $element);
        }
    }

    public function test_iterates_prev_and_next(): void
    {
        $arr = Arr::make([100, 101, 102]);
        $arr->next();
        $arr->next();
        $element = $arr->current();
        $this->assertEquals(102, $element);
        $arr->prev();
        $element = $arr->current();
        $this->assertEquals(101, $element);
    }
}
