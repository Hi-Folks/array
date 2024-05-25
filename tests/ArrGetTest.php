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


it('Basic getArr', function (): void {
    $arr = Arr::make(['A','B','C']);
    expect($arr->getArr(1))->toBeInstanceOf(Arr::class);
    expect($arr->getArr(1)->at(0))->toBe('B');
    expect($arr->getArrNullable(4))->toBeNull();
    expect($arr->getArr(4, 'AAAA'))->toBeInstanceOf(Arr::class);
    expect($arr->getArr(4, 'AAAA')->get(0))->toBe('AAAA');


    $arr = Arr::make([
        'A' => 'First',
        'B' => ['some', 'thing'],
        'C' => [ 'nested-item-1' => 10, 'nested-item-2' => 20],
        'D' => []
    ]);

    expect($arr->getArr('C')->get('nested-item-1'))->toBe(10);
    expect($arr->getArr('C')->entries()->get(0))->toBeArray();
    expect($arr->getArr('C')->entries()->get(0))->toBe(['nested-item-1',10]);
    expect($arr->getArr('C')->keys())->toBe(['nested-item-1','nested-item-2' ]);

    $arr = Arr::make(
        [
            "avocado" =>
                [
                    'name' => 'Avocado',
                    'fruit' => '🥑',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Avocado'
                ],
            "apple" =>
                [
                    'name' => 'Apple',
                    'fruit' => '🍎',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Apple'
                ],
            "banana" =>
                [
                    'name' => 'Banana',
                    'fruit' => '🍌',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Banana'
                ],
            "cherry" =>
                [
                    'name' => 'Cherry',
                    'fruit' => '🍒',
                    'wikipedia' => 'https://en.wikipedia.org/wiki/Cherry'
                ],
        ]
    );
    $appleArr = $arr->getArr("apple");
    expect($appleArr->count())->toBe(3)
        ->and($appleArr->get("name"))->toBe('Apple');
    $appleNameArr = $arr->getArr("apple.name");
    expect($appleNameArr->get(0))->toBe('Apple');
    $appleNoExists = $arr->getArr("apple.name.noexists");
    expect($appleNoExists)->toBeInstanceOf(Arr::class);
    expect($appleNoExists->count())->toBe(0);
    $appleNoExists = $arr->getArrNullable("apple.name.noexists");
    expect($appleNoExists)->toBeNull();
    $appleNoExists = $arr->getArr("apple.name.noexists", "some");
    expect($appleNoExists)->toBeInstanceOf(Arr::class)
        ->and($appleNoExists->get(0))->toBe("some");
});
