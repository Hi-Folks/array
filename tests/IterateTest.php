<?php

use HiFolks\DataType\Arr;

it('iterates', function (): void {
    $arr = Arr::make([100, 101, 102]);
    foreach ($arr as $element) {
        expect($element)->toBeGreaterThanOrEqual(100);
        expect($element)->toBeLessThanOrEqual(102);
    }
});

it('iterates prev and next', function (): void {
    $arr = Arr::make([100, 101, 102]);
    $arr->next();
    $arr->next();
    $element = $arr->current();
    expect($element)->toEqual(102);
    $arr->prev();
    $element = $arr->current();
    expect($element)->toEqual(101);
});
