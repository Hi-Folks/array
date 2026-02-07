<?php

namespace HiFolks\Array\Tests\Arr;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class ArrFromTest extends TestCase
{
    public function test_converts_a_string_to_an_array(): void
    {
        $result = Arr::from('foo')->arr();
        $this->assertIsArray($result);
        $this->assertSame(['f', 'o', 'o'], $result);
    }

    public function test_converts_a_generator_to_an_array(): void
    {
        $generator = function (): \Generator {
            yield 1;
            yield 8;
            yield 7;
        };

        $result = Arr::from($generator())->arr();
        $this->assertIsArray($result);
        $this->assertSame([1, 8, 7], $result);
    }

    public function test_takes_a_function_to_map_over_the_given_array(): void
    {
        $result = Arr::from([1, 2, 3], fn ($x): float|int|array => $x + $x)->arr();
        $this->assertIsArray($result);
        $this->assertSame([2, 4, 6], $result);
    }
}
