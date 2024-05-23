<?php

use HiFolks\DataType\Arr;

it('is Array', function (): void {
    $arr = Arr::make();
    expect($arr->arr())->toBeArray();
});

it('creates Arr from function', function (): void {
    $arr = Arr::fromFunction(fn (): int => random_int(0, 100), 500);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(500);

    expect($arr->every(fn ($element): bool => $element >= 0))->toBeTrue();
    expect($arr->every(fn ($element): bool => $element <= 100))->toBeTrue();

    $arr = Arr::fromFunction(fn ($i) => $i, 5000);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(5000);
    //print_result($arr);

    expect($arr->every(fn ($element): bool => $element >= 0))->toBeTrue();
    expect($arr->every(fn ($element): bool => $element <= 5000))->toBeTrue();
});

it('creates Arr from value', function (): void {
    $arr = Arr::fromValue(0, 5000);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(5000);
    expect($arr->every(fn ($element): bool => $element === 0))->toBeTrue();
});

it('is Array empty', function (): void {
    $arr = Arr::make();
    expect($arr->arr())->toBeArray();
    expect(count($arr->arr()))->toEqual(0);
    expect($arr->count())->toEqual(0);
    expect($arr->length())->toEqual(0);
});
it('is Array length 1', function (): void {
    $arr = Arr::make([99]);
    expect($arr->arr())->toBeArray();
    expect(count($arr->arr()))->toEqual(1);
    expect($arr->count())->toEqual(1);
    expect($arr->length())->toEqual(1);
});

it('is Array forEach', function (): void {
    $arr = Arr::make([99, 98]);
    expect($arr->arr())->toBeArray();
    expect(count($arr->arr()))->toEqual(2);
    expect($arr->count())->toEqual(2);
    expect($arr->length())->toEqual(2);
    expect($arr->push(100))->toEqual(3);
    expect($arr->length())->toEqual(3);
    $x = $arr->forEach(fn ($element, $key): string => $key * $element.PHP_EOL);
    expect($x->length())->toEqual(3);
    expect($x->get(2))->toEqual(200);
});

it('shift array', function (): void {
    $arr = Arr::make([99, 98, 97]);
    expect($arr->length())->toEqual(3);
    expect($arr->shift())->toEqual(99);
    expect($arr->length())->toEqual(2);
});

it('unshift array', function (): void {
    $arr = Arr::make([99, 98, 97]);
    expect($arr->length())->toEqual(3);
    expect($arr->unshift(200))->toEqual(4);
    expect($arr->get(0))->toEqual(200);
    expect($arr->get(1))->toEqual(99);

    $arr = Arr::make([1, 2]);
    $arr->unshift(0);
    expect($arr->toString())->toEqual('0,1,2');
    $arr->unshift(-2, -1);
    expect($arr->toString())->toEqual('-2,-1,0,1,2');
});
it('append array', function (): void {
    $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
    expect($arr->length())->toEqual(6);
    $arr->append([12], [13, 14]);
    expect($arr->length())->toEqual(9);
    $arr = Arr::make();
    $arr->append([11]);
    expect($arr->length())->toEqual(1);
    $number = $arr->pop();
    expect($arr->length())->toEqual(0);
    expect($number)->toEqual(11);
});
it('joins arrays', function (): void {
    $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
    expect($arr->join())->toEqual('99,98,97,1,2,3');
});

it('concats arrays', function (): void {
    $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
    $arr2 = $arr->concat([1000, 1001]);
    expect($arr2->arr())->toBeArray();
    expect($arr2->length())->toEqual(8);
    expect($arr->length())->toEqual(6);
});
it('concats more types', function (): void {
    $arr = Arr::make([99, 98, 97])->concat([1, 2, 3], [1000, 1001]);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(8);
    $arr2 = $arr->concat(9, true);
    expect($arr2->length())->toEqual(10);
    $arr3 = $arr->concat($arr2);
    expect($arr3->length())->toEqual(18);
});
it('concats indexed/associative arrays', function (): void {
    $fruits = Arr::make([
        3 => '🥝',
        -1 => '🍓',
        1 => '🍋',
        'mango' => '🥭',
        'apple' => '🍎',
        'banana' => '🍌', ]);
    $fruits2 = $fruits->concat(['🍊', '🍍']);

    expect($fruits2->arr())->toBeArray();
    expect($fruits2->length())->toEqual(8);
    expect($fruits2['mango'])->toEqual('🥭');
    expect($fruits2[4])->toEqual('🍍');
});
it('slices arrays', function (): void {
    $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
    $arr2 = $arr->slice(1, 2);
    expect($arr2->arr())->toBeArray();
    expect($arr2->length())->toEqual(1);
    expect($arr2->get(0))->toEqual(98);
    expect($arr2->get(1))->toBeNull();
    expect($arr->length())->toEqual(6);

    $animals = Arr::make(['ant', 'bison', 'camel', 'duck', 'elephant']);
    $arr = $animals->slice(2, 4);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(2);
    expect($arr[0])->toEqual('camel');
    expect($arr[1])->toEqual('duck');
    expect($arr->get(2))->toBeNull();

    $arr = $animals->slice(2);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(3);
    expect($arr[0])->toEqual('camel');
    expect($arr[1])->toEqual('duck');
    expect($arr[2])->toEqual('elephant');
    expect($arr->get(3))->toBeNull();
    expect($arr[3])->toBeNull();

    $arr = $animals->slice(1, 5);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(4);
    expect($arr[0])->toEqual('bison');
    expect($arr[1])->toEqual('camel');
    expect($arr[2])->toEqual('duck');
    expect($arr[3])->toEqual('elephant');
    expect($arr->get(4))->toBeNull();
    expect($arr[4])->toBeNull();

    $arr = $animals->slice(-2);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(2);
    expect($arr[0])->toEqual('duck');
    expect($arr[1])->toEqual('elephant');
    expect($arr->get(2))->toBeNull();
    expect($arr[2])->toBeNull();

    $arr = $animals->slice(2, -1);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(2);
    expect($arr[0])->toEqual('camel');
    expect($arr[1])->toEqual('duck');
    expect($arr->get(2))->toBeNull();
    expect($arr[2])->toBeNull();

    $arr = $animals->slice(-3, -1);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(2);
    expect($arr[0])->toEqual('camel');
    expect($arr[1])->toEqual('duck');
    expect($arr->get(2))->toBeNull();
    expect($arr[2])->toBeNull();

    $arr = $animals->slice(27, -1);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(0);
    expect($arr[0])->toBeNull();
});

it('searches arrays', function (): void {
    $arr = Arr::make([99, 98, 97])->append([1, 2, 3]);
    $index = $arr->indexOf(1);
    expect($index)->toBeInt();
    expect($index)->toEqual(3);
    expect($arr->length())->toEqual(6);
    $arr = Arr::make(['L' => 'Leon', 'T' => 'Tiger', 'B' => 'Bird']);
    $index = $arr->indexOf('Tiger');
    expect($index)->toBeString();
    expect($index)->toEqual('T');
    expect($arr->length())->toEqual(3);
});

it('searches last occurrence arrays', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    $index = $arr->lastIndexOf(5);
    expect($index)->toBeInt();
    expect($index)->toEqual(12);
    expect($arr->length())->toEqual(17);
    $arr = Arr::make(['L1' => 'Leon', 'T1' => 'Tiger', 'B1' => 'Bird', 'T2' => 'Tiger', 'B2' => 'Bird']);
    $index = $arr->lastIndexOf('Tiger');
    expect($index)->toBeString();
    expect($index)->toEqual('T2');
    expect($arr->length())->toEqual(5);
});

it('matches every element', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    $bool = $arr->every(fn ($element): bool => $element > 0);
    expect($bool)->toEqual(true);
    $bool = $arr->every(fn ($element): bool => $element > 1);
    expect($bool)->toEqual(false);
    $bool = $arr->every(fn ($element): bool => $element > 10000);
    expect($bool)->toEqual(false);
});

it('matches some element', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    $bool = $arr->some(fn ($element): bool => $element > 0);
    expect($bool)->toEqual(true);
    $bool = $arr->some(fn ($element): bool => $element > 1);
    expect($bool)->toEqual(true);
    $bool = $arr->some(fn ($element): bool => $element > 10000);
    expect($bool)->toEqual(false);

    $arr = Arr::make([1, 2, 3, 4, 5]);
    $even = fn ($element): bool => $element % 2 === 0;
    $bool = $arr->some($even);
    expect($bool)->toEqual(true);
});

it('filters some element', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    $arr2 = $arr->filter(fn ($element): bool => $element > 3);
    expect($arr->length())->toEqual(17);
    expect($arr2->length())->toEqual(11);
    expect($arr->every(fn ($element): bool => $element > 0))->toBeTrue();
    expect($arr->every(fn ($element): bool => $element <= 3))->toBeFalse();
    expect($arr2->every(fn ($element): bool => $element > 3))->toBeTrue();
});

it('finds the first element')
    ->with([
        'string|int arrays' => (object)[
            'intArr' => Arr::make([1,2,3,4,5,6,7,8,9,0,11]),
            'strArr' => Arr::make(['begin','middle','end'])
        ],
    ])
    ->expect(fn ($dataset) => $dataset->intArr->find(fn ($element): bool => $element > 0))
        ->toBeInt()->toEqual(1)
    ->expect(fn ($dataset) => $dataset->intArr->find(fn ($element): bool => $element < 5))
        ->toBeInt()->toEqual(1)
    ->expect(fn ($dataset) => $dataset->intArr->find(fn ($element): bool => $element > 5))
        ->toBeInt()->toEqual(6)
    ->expect(fn ($dataset) => $dataset->intArr->find(fn ($element, $index): bool => $element > 1 && $index > 1))
        ->toBeInt()->toEqual(3)
    ->expect(fn ($dataset) => $dataset->intArr->find(fn ($element): bool => $element > 50))
        ->toBeNull()
    ->expect(fn ($dataset) => $dataset->strArr->find(fn ($element): bool => $element == 'end'))
        ->toBeString()->toBe('end')
    ->expect(fn ($dataset) => $dataset->strArr->find(fn ($element): bool => str_contains((string) $element, 'e')))
        ->toBeString()->toBe('begin')
    ->expect(fn ($dataset) => $dataset->strArr->find(fn ($element): bool => $element == 'prefix'))
        ->toBeNull();

it('finds the first index of some element', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    $index = $arr->findIndex(fn ($element): bool => $element > 0);
    expect($index)->toBeInt();
    expect($index)->toEqual(0);
    $index = $arr->findIndex(fn ($element): bool => $element > 1);
    expect($index)->toEqual(1);
    $index = $arr->findIndex(fn ($element): bool => $element > 10000);
    expect($index)->toEqual(-1);
    $index = $arr->findIndex(fn ($element, $index): bool => $element > 1 && $index > 1);
    expect($index)->toEqual(2);
});

it('maps elements', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    $arr2 = $arr->map(fn ($element): int|float => $element + 1);

    expect($arr->length())->toEqual(17);
    expect($arr2->length())->toEqual(17);
    expect($arr->every(fn ($element): bool => $element > 0))->toBeTrue();
    expect($arr->every(fn ($element): bool => $element > 1))->toBeFalse();
    expect($arr2->every(fn ($element): bool => $element > 1))->toBeTrue();
});

it('flats array', function (): void {
    $arr = Arr::make([1, [2, 3], 4, [5, 6, 7]]);

    $arr2 = $arr->flat();
    expect($arr->length())->toEqual(4);
    expect($arr2->length())->toEqual(7);
});

it('flats and maps array', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);

    $arr2 = $arr->flatMap(fn ($element): array => [$element, $element * 2]);
    expect($arr->length())->toEqual(7);
    expect($arr2->length())->toEqual(14);
    expect($arr2->arr())->toEqual([1, 2, 2, 4, 3, 6, 4, 8, 5, 10, 6, 12, 7, 14]);

    $arr2 = $arr->flatMap(fn ($element) => $element);
    expect($arr->length())->toEqual(7);
    expect($arr2->length())->toEqual(7);
    expect($arr2->arr())->toEqual([1, 2, 3, 4, 5, 6, 7]);
});

it('fills array', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
    expect($arr->length())->toEqual(7);
    $arr->fill(0, 0, 3);
    expect($arr->length())->toEqual(7);
    expect($arr[0])->toEqual(0);
    expect($arr[1])->toEqual(0);
    expect($arr[2])->toEqual(0);
    expect($arr[3])->toEqual(0);
    expect($arr[4])->toEqual(5);
    expect($arr[5])->toEqual(6);
    expect($arr[6])->toEqual(7);
    $arr2 = $arr->filter(fn ($element): bool => $element == 0);
    expect($arr2->length())->toEqual(4);
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
    $arr->fill(0);
    $allAreZeros = $arr->every(fn ($element): bool => $element === 0);
    expect($arr->length())->toEqual(7);
    expect($allAreZeros)->toBeTrue();
});

it('reduces Arr', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
    $value = $arr->reduce(fn ($previousValue, $currentValue): float|int|array => $previousValue + $currentValue);
    expect($value)->toBeInt();
    expect($value)->toEqual(28);
});

it('reduces Arr in reverse way', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
    $value = $arr->reduceRight(fn ($previousValue, $currentValue): float|int|array => $previousValue + $currentValue);
    expect($value)->toBeInt();
    expect($value)->toEqual(28);
});

it('reverses Arr', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7]);
    $arr2 = $arr->reverse();
    expect($arr2->length())->toEqual(7);
    expect($arr2->join())->toEqual('7,6,5,4,3,2,1');
    expect($arr->join())->toEqual('7,6,5,4,3,2,1');
    $arr->push(0);
    expect($arr2->join())->toEqual('7,6,5,4,3,2,1');
    expect($arr->join())->toEqual('7,6,5,4,3,2,1,0');
});

it('sorts Arr', function (): void {
    $arr = Arr::make([6, 2, 4, 2, 1, 9, 7]);
    expect($arr->join())->toEqual('6,2,4,2,1,9,7');
    $arr->sort();
    expect($arr->length())->toEqual(7);
    expect($arr->join())->toEqual('1,2,2,4,6,7,9');
});
it('sorts and change Arr', function (): void {
    $months = Arr::make(['March', 'Jan', 'Feb', 'Dec']);
    $monthsSorted = $months->sort();
    expect($months->length())->toEqual(4);
    expect($monthsSorted->length())->toEqual(4);
    expect($months[3])->toEqual('March');
    expect($monthsSorted[3])->toEqual('March');
    $month = $months->pop();
    expect($month)->toEqual('March');
    expect($months[3])->toBeNull();
    expect($monthsSorted[3])->toBeNull();
});

it('splices Arr', function (): void {
    $months = Arr::make(['Jan', 'March', 'April', 'June']);
    $months->splice(1, 0, 'Feb');
    expect($months->join())->toEqual('Jan,Feb,March,April,June');
    $months->splice(4, 1, 'May');
    expect($months->join())->toEqual('Jan,Feb,March,April,May');
    $months->splice(1);
    expect($months->join())->toEqual('Jan');
});

it('stringifies an Arr', function (): void {
    $months = Arr::make(['Jan', 'Feb', 'March', 'April', 'May']);
    expect($months->toString())->toEqual('Jan,Feb,March,April,May');

    $arr = Arr::make([1, 2, 'a', '1a']);
    expect($arr->toString())->toEqual('1,2,a,1a');
});

it('checks is array', function (): void {
    $isArray = Arr::isArray(['Jan', 'Feb', 'March', 'April', 'May']);
    expect($isArray)->toEqual(true);
    $isArray = Arr::isArray(null);
    expect($isArray)->toEqual(false);
    $isArray = Arr::isArray(1);
    expect($isArray)->toEqual(false);
    $isArray = Arr::isArray(0);
    expect($isArray)->toEqual(false);
});

it('tests if array is empty', function (): void {
    $emptyArr = Arr::make([]);
    expect($emptyArr->isEmpty())->toEqual(true);
    $notEmptyArr = Arr::make([1]);
    expect($notEmptyArr->isEmpty())->toEqual(false);
});

it('chainable empty array', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    expect($arr->filter(fn ($element): bool => $element > 100)->isEmpty())->toBeTrue();
    expect($arr->filter(fn ($element): bool => $element < 100)->isEmpty())->toBeFalse();
});

it('implements of() method', function (): void {
    $arr = Arr::of('Jan', 'Feb', 'March', 'April', 'May');
    expect($arr->length())->toEqual(5);
    expect($arr[4])->toEqual('May');

    $arr = Arr::of(7);
    expect($arr->length())->toEqual(1);
    expect($arr[0])->toEqual(7);
});

it('tests keys() method', function (): void {
    $arr = Arr::make(
        [
            '01' => 'Jan', '02' => 'Feb', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
            '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
        ]
    );
    expect($arr->length())->toEqual(12);
    $arrKeys = $arr->keys(true);
    expect($arrKeys)->toBeObject();
    expect($arrKeys->length())->toEqual(12);
    expect($arrKeys[4])->toEqual('05');

    $keys = $arr->keys();
    expect($keys)->toBeArray();
    expect(is_countable($keys) ? count($keys) : 0)->toEqual(12);
    expect($keys[4])->toEqual('05');

    foreach ($arrKeys as $key => $value) {
        expect($arrKeys[$key])->toEqual($keys[$key]);
    }
});

it('test keys() method', function (): void {
    $arr = Arr::make(
        [5, 12, 8, 130, 44]
    );
    expect($arr->length())->toEqual(5);
    expect($arr->at(2))->toEqual(8);
    expect($arr->at(-2))->toEqual(130);
    expect($arr->at(100))->toBeNull();
});

it('includes element', function (): void {
    $arr = Arr::make(
        [1, 2, 3]
    );
    expect($arr->length())->toEqual(3);
    expect($arr->includes(2))->toBeTrue();
    expect($arr->includes('2'))->toBeFalse();

    $arr = Arr::make(
        ['cat', 'dog', 'bat']
    );
    expect($arr->length())->toEqual(3);
    expect($arr->includes('cat'))->toBeTrue();
    expect($arr->includes('Cat'))->toBeFalse();
    expect($arr->includes('at'))->toBeFalse();
});

it('includes element from index', function (): void {
    $arr = Arr::make(
        [1, 2, 3]
    );
    expect($arr->length())->toEqual(3);
    expect($arr->includes(2))->toBeTrue();
    expect($arr->includes(4))->toBeFalse();
    expect($arr->includes(3, 3))->toBeFalse();
    expect($arr->includes(3, 2))->toBeTrue();
    expect($arr->includes(3, -1))->toBeTrue();

    $arr = Arr::make(
        ['a', 'b', 'c']
    );
    expect($arr->includes('c', 3))->toBeFalse();
    expect($arr->includes('c', 100))->toBeFalse();

    expect($arr->includes('a', -100))->toBeTrue();
    expect($arr->includes('b', -100))->toBeTrue();
    expect($arr->includes('c', -100))->toBeTrue();
    expect($arr->includes('a', -2))->toBeFalse();
});

it(' extract values', function (): void {
    $fruits = Arr::make([
        7 => '🥝',
        -1 => '🍓',
        1 => '🍋',
        'mango' => '🥭',
        'apple' => '🍎',
        'banana' => '🍌',
        '🍊',
        '🍍', ]);

    expect($fruits->arr())->toBeArray();
    expect($fruits->length())->toEqual(8);
    expect($fruits['mango'])->toEqual('🥭');
    expect($fruits[9])->toEqual('🍍');

    $onlyFruits = $fruits->values();
    expect($onlyFruits->arr())->toBeArray();
    expect($onlyFruits->length())->toEqual(8);
    expect($onlyFruits[3])->toEqual('🥭');
    expect($onlyFruits[7])->toEqual('🍍');
    $i = 0;
    foreach ($fruits->values() as $value) {
        $i++;
    }
    expect($i)->toEqual(8);
});

it('creates entries', function (): void {
    $fruits = Arr::make([
        7 => '🥝',
        -1 => '🍓',
        1 => '🍋',
        'mango' => '🥭',
        'apple' => '🍎',
        'banana' => '🍌',
        '🍊',
        '🍍',
    ]);

    $entries = $fruits->entries();
    expect($entries->arr())->toBeArray();
    expect($entries->length())->toEqual(8);
    expect($entries)->toEqual(Arr::make([
        [7, '🥝'],
        [-1, '🍓'],
        [1, '🍋'],
        ['mango', '🥭'],
        ['apple', '🍎'],
        ['banana', '🍌'],
        [8, '🍊'],
        [9, '🍍'],
    ]));
});

it('tests copyWithin() method with one parameter', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5]);

    $result = $arr->copyWithin(-2);
    expect($result)
        ->toBeArray()
        ->toHaveCount(5)
        ->toEqual([1, 2, 3, 1, 2]);
});

it('tests copyWithin() method with two parameters', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5]);

    $result = $arr->copyWithin(0, 3);
    expect($result)
        ->toBeArray()
        ->toHaveCount(5)
        ->toEqual([4, 5, 3, 4, 5]);
});

it('tests copyWithin() method with all the parameters', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5]);

    $result = $arr->copyWithin(0, 3, 4);
    expect($result)
        ->toBeArray()
        ->toHaveCount(5)
        ->toEqual([4, 2, 3, 4, 5]);
});

it('tests copyWithin() method with all the parameters negative', function (): void {
    $arr = Arr::make([1, 2, 3, 4, 5]);

    $result = $arr->copyWithin(-2, -3, -1);
    expect($result)
        ->toBeArray()
        ->toHaveCount(5)
        ->toEqual([1, 2, 3, 3, 4]);
});

it('tests flatMap can handle an array of arrays', function (): void {
    $arr = Arr::make([
        [1, 2],
        [3, 4]
    ]);

    $result = $arr->flatMap(fn ($element): int|float => $element * 2);
    expect($result->length())->toEqual(4);
    expect($result->arr())->toEqual([2, 4, 6, 8]);
});

it('can unset array elements by their keys', function (): void {
    $arr = Arr::make([
        'mango' => '🥭',
        'apple' => '🍎',
        'banana' => '🍌'
    ]);

    $arr->unset('apple');
    expect($arr)
        ->toHaveCount(2)
        ->and($arr->arr())
        ->toMatchArray([
            'mango' => '🥭',
            'banana' => '🍌'
        ])
        ->and($arr->unset('orange'))
        ->toBeFalse()
        ->and($arr)
        ->toHaveCount(2)
        ->and($arr->arr())
        ->toMatchArray([
            'mango' => '🥭',
            'banana' => '🍌'
        ]);
});

it('can set array key', function (): void {
    $arr = Arr::make([
        'mango' => '🥭',
        'banana' => '🍌'
    ]);

    $arr->set('apple', '🍎');
    expect($arr)
        ->toHaveCount(3)
        ->and($arr->arr())
        ->toMatchArray([
            'mango' => '🥭',
            'banana' => '🍌',
            'apple' => '🍎'
        ]);
});
