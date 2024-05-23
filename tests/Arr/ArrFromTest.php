<?php

use HiFolks\DataType\Arr;

it('converts a string to an array', function (): void {
    expect(Arr::from('foo')->arr())
        ->toBeArray()
        ->toBe(['f','o','o']);
});

it('converts a generator to an array', function (): void {
    function generator(): Generator
    {
        yield 1;
        yield 8;
        yield 7;
    }

    expect(Arr::from(generator())->arr())
        ->toBeArray()
        ->toBe([1, 8, 7]);
});

it('takes a function to map over the given array', function (): void {
    expect(Arr::from([1, 2, 3], fn ($x): float|int|array => $x + $x)->arr())
        ->toBeArray()
        ->toBe([2, 4, 6]);
});
