<?php

use HiFolks\DataType\Arr;

it('sum Array', function (): void {
    $arr = Arr::make([1, 2, 3]);
    expect($arr->sum())->toEqual(6);

    $arr = Arr::make(['3', 2, 3]);
    expect($arr->sum())->toEqual(8);
    $arr = Arr::fromValue(1, 100);
    expect($arr->sum())->toEqual(100);
});

it('avg Array', function (): void {
    $arr = Arr::make([1, 2, 3]);
    expect($arr->avg())->toEqual(2);

    $arr = Arr::fromValue(1, 100);
    expect($arr->avg())->toEqual(1);

    $arr = Arr::fromValue(1.2, 100);
    expect(round($arr->avg(), 2))->toEqual(1.2);

    $arr = Arr::make([1.1, 1.5]);
    expect($arr->avg())->toEqual(1.3);

    $arr = Arr::make([]);
    expect($arr->avg())->toEqual(0);
});
