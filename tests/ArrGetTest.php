<?php

use HiFolks\DataType\Arr;

it('Basic get', function (): void {
    $arr = Arr::make(['A','B','C']);
    expect($arr->get(1))->toBe('B');
    expect($arr->get(4))->toBeNull();
    expect($arr->get(4, 'AAAA'))->toBe("AAAA");
});

it('Basic nested get', function (): void {
    $arr = Arr::make([
        'A' => 'First',
        'B' => ['some', 'thing'],
        'C' => [ 'nested-item-1' => 10, 'nested-item-2' => 20],
        'D' => []
    ]);
    expect($arr->get('A'))->toBe('First');
    expect($arr->get('B'))->toBeArray();
    expect($arr->get('B.0'))->toBe('some');
    expect($arr->get('B.1'))->toBe('thing');
    expect($arr->get('B.2'))->toBeNull();
    expect($arr->get('B.2', 1234))->toBe(1234);
    expect($arr->get('B#0', charNestedKey: '#'))->toBe('some');
    expect($arr->get('B#1', charNestedKey: '#'))->toBe('thing');
    expect($arr->get('B#2', charNestedKey: '#'))->toBeNull();
    expect($arr->get('B#2', 1234, '#'))->toBe(1234);

    expect($arr->get('C.0'))->toBeNull();
    expect($arr->get('C.nested-item-1'))->toBe(10);
    expect($arr->get('C.nested-item-2'))->toBe(20);
    expect($arr->get('C.nested-item-2.other'))->toBeNull();
    expect($arr->get('C.nested-item-2.other', 'zzz'))->toBe('zzz');
    expect($arr->get('C#nested-item-2#other', 'zzz', '#'))->toBe('zzz');
    expect($arr->get('C#nested-item-2#other', 'zzz'))->toBe('zzz');
    expect($arr->get('C#nested-item-2', 'zzz'))->toBe('zzz');
    expect($arr->get('C#nested-item-4', 'zzz'))->toBe('zzz');
    expect($arr->get('D#nested-item-4', 'zzz'))->toBe('zzz');
    expect($arr->get('D.0'))->toBeNull();
    expect($arr->get('D', '0'))->toBeArray()->toHaveLength(0);

});
