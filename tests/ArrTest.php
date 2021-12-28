<?php

use HiFolks\DataType\Arr;

it('is Array', function () {
    $arr = Arr::make();
    expect($arr->arr())->toBeArray();
});

it('creates Arr from function', function () {
    $arr = Arr::fromFunction(fn () => random_int(0, 100), 500);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(500);

    expect($arr->every(fn ($element) => $element >= 0))->toBeTrue();
    expect($arr->every(fn ($element) => $element <= 100))->toBeTrue();

    $arr = Arr::fromFunction(fn ($i) => $i, 5000);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(5000);
    //print_result($arr);

    expect($arr->every(fn ($element) => $element >= 0))->toBeTrue();
    expect($arr->every(fn ($element) => $element <= 5000))->toBeTrue();
});

it('creates Arr from value', function () {
    $arr = Arr::fromValue(0, 5000);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(5000);
    expect($arr->every(fn ($element) => $element === 0))->toBeTrue();
});

it('is Array empty', function () {
    $arr = Arr::make();
    expect($arr->arr())->toBeArray();
    expect(count($arr->arr()))->toEqual(0);
    expect($arr->count())->toEqual(0);
    expect($arr->length())->toEqual(0);
});
it('is Array length 1', function () {
    $arr = Arr::make([99]);
    expect($arr->arr())->toBeArray();
    expect(count($arr->arr()))->toEqual(1);
    expect($arr->count())->toEqual(1);
    expect($arr->length())->toEqual(1);
});

it('is Array forEach', function () {
    $arr = Arr::make([99,98]);
    expect($arr->arr())->toBeArray();
    expect(count($arr->arr()))->toEqual(2);
    expect($arr->count())->toEqual(2);
    expect($arr->length())->toEqual(2);
    expect($arr->push(100))->toEqual(3);
    expect($arr->length())->toEqual(3);
    $x = $arr->forEach(function ($element, $key) {
        return $key * $element . PHP_EOL;
    });
    expect($x->length())->toEqual(3);
    expect($x->get(2))->toEqual(200);
});

it('shift array', function () {
    $arr = Arr::make([99,98, 97]);
    expect($arr->length())->toEqual(3);
    expect($arr->shift())->toEqual(99);
    expect($arr->length())->toEqual(2);
});

it('unshift array', function () {
    $arr = Arr::make([99,98, 97]);
    expect($arr->length())->toEqual(3);
    expect($arr->unshift(200))->toEqual(4);
    expect($arr->get(0))->toEqual(200);
    expect($arr->get(1))->toEqual(99);
});
it('append array', function () {
    $arr = Arr::make([99,98, 97])->append([1,2,3]);
    expect($arr->length())->toEqual(6);
    $arr->append([12], [13,14]);
    expect($arr->length())->toEqual(9);
    $arr = Arr::make();
    $arr->append([11]);
    expect($arr->length())->toEqual(1);
    $number = $arr->pop();
    expect($arr->length())->toEqual(0);
    expect($number)->toEqual(11);
});
it('joins arrays', function () {
    $arr = Arr::make([99,98, 97])->append([1,2,3]);
    expect($arr->join())->toEqual("99,98,97,1,2,3");
});

it('concats arrays', function () {
    $arr = Arr::make([99,98, 97])->append([1,2,3]);
    $arr2 = $arr->concat([1000,1001]);
    expect($arr2->arr())->toBeArray();
    expect($arr2->length())->toEqual(8);
    expect($arr->length())->toEqual(6);
});
it('concats more types', function () {
    $arr = Arr::make([99,98, 97])->concat([1,2,3], [1000,1001]);
    expect($arr->arr())->toBeArray();
    expect($arr->length())->toEqual(8);
    $arr2 = $arr->concat(9, true);
    expect($arr2->length())->toEqual(10);
    $arr3 = $arr->concat($arr2);
    expect($arr3->length())->toEqual(18);
});
it('slices arrays', function () {
    $arr = Arr::make([99,98, 97])->append([1,2,3]);
    $arr2 = $arr->slice(1, 2);
    expect($arr2->arr())->toBeArray();
    expect($arr2->length())->toEqual(2);
    expect($arr2->get(0))->toEqual(98);
    expect($arr2->get(1))->toEqual(97);
    expect($arr->length())->toEqual(6);
});

it('searches arrays', function () {
    $arr = Arr::make([99,98, 97])->append([1,2,3]);
    $index = $arr->indexOf(1);
    expect($index)->toBeInt();
    expect($index)->toEqual(3);
    expect($arr->length())->toEqual(6);
    $arr = Arr::make(["L" => "Leon","T" => "Tiger", "B" => "Bird"]);
    $index = $arr->indexOf("Tiger");
    expect($index)->toBeString();
    expect($index)->toEqual("T");
    expect($arr->length())->toEqual(3);
});

it('searches last occurrence arrays', function () {
    $arr = Arr::make([1,2,3,4,5,6,7,8,9,8,7,6,5,4,3,2,1]);
    $index = $arr->lastIndexOf(5);
    expect($index)->toBeInt();
    expect($index)->toEqual(12);
    expect($arr->length())->toEqual(17);
    $arr = Arr::make(["L1" => "Leon","T1" => "Tiger", "B1" => "Bird", "T2" => "Tiger","B2" => "Bird"]);
    $index = $arr->lastIndexOf("Tiger");
    expect($index)->toBeString();
    expect($index)->toEqual("T2");
    expect($arr->length())->toEqual(5);
});

it('matches every element', function () {
    $arr = Arr::make([1,2,3,4,5,6,7,8,9,8,7,6,5,4,3,2,1]);
    $bool = $arr->every(fn ($element) => $element > 0);
    expect($bool)->toEqual(true);
    $bool = $arr->every(fn ($element) => $element > 1);
    expect($bool)->toEqual(false);
    $bool = $arr->every(fn ($element) => $element > 10000);
    expect($bool)->toEqual(false);
});

it('matches some element', function () {
    $arr = Arr::make([1,2,3,4,5,6,7,8,9,8,7,6,5,4,3,2,1]);
    $bool = $arr->some(fn ($element) => $element > 0);
    expect($bool)->toEqual(true);
    $bool = $arr->some(fn ($element) => $element > 1);
    expect($bool)->toEqual(true);
    $bool = $arr->some(fn ($element) => $element > 10000);
    expect($bool)->toEqual(false);
});

it('filters some element', function () {
    $arr = Arr::make([1,2,3,4,5,6,7,8,9,8,7,6,5,4,3,2,1]);
    $arr2 = $arr->filter(fn ($element) => $element > 3);
    expect($arr->length())->toEqual(17);
    expect($arr2->length())->toEqual(11);
    expect($arr->every(fn ($element) => $element > 0))->toBeTrue();
    expect($arr->every(fn ($element) => $element <= 3))->toBeFalse();
    expect($arr2->every(fn ($element) => $element > 3))->toBeTrue();
});
it('maps elements', function () {
    $arr = Arr::make([1,2,3,4,5,6,7,8,9,8,7,6,5,4,3,2,1]);
    $arr2 = $arr->map(fn ($element) => $element + 1);

    expect($arr->length())->toEqual(17);
    expect($arr2->length())->toEqual(17);
    expect($arr->every(fn ($element) => $element > 0))->toBeTrue();
    expect($arr->every(fn ($element) => $element > 1))->toBeFalse();
    expect($arr2->every(fn ($element) => $element > 1))->toBeTrue();
});

it('flats array', function () {
    $arr = Arr::make([ 1, [2,3], 4 , [5,6,7]]);
    ;
    $arr2 = $arr->flat();
    expect($arr->length())->toEqual(4);
    expect($arr2->length())->toEqual(7);
});

it('flats and maps array', function () {
    $arr = Arr::make([ 1,2,3,4,5,6,7]);

    $arr2 = $arr->flatMap(fn ($element) => [$element, $element * 2]);
    expect($arr->length())->toEqual(7);
    expect($arr2->length())->toEqual(14);
    $arr2 = $arr->flatMap(fn ($element) => $element);
    expect($arr->length())->toEqual(7);
    expect($arr2->length())->toEqual(7);
});

it('fills array', function () {
    $arr = Arr::make([ 1,2,3,4,5,6,7]);
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
    $arr2 = $arr->filter(fn ($element) => $element == 0);
    expect($arr2->length())->toEqual(4);
    $arr = Arr::make([ 1,2,3,4,5,6,7]);
    $arr->fill(0);
    $allAreZeros = $arr->every(fn ($element) => $element === 0);
    expect($arr->length())->toEqual(7);
    expect($allAreZeros)->toBeTrue();
});

it('reduces Arr', function () {
    $arr = Arr::make([ 1,2,3,4,5,6,7]);
    $value = $arr->reduce(fn ($previousValue, $currentValue) => $previousValue + $currentValue);
    expect($value)->toBeInt();
    expect($value)->toEqual(28);
});

it('reduces Arr in reverse way', function () {
    $arr = Arr::make([ 1,2,3,4,5,6,7]);
    $value = $arr->reduceRight(fn ($previousValue, $currentValue) => $previousValue + $currentValue);
    expect($value)->toBeInt();
    expect($value)->toEqual(28);
});

it('reverses Arr', function () {
    $arr = Arr::make([1,2,3,4,5,6,7]);
    $arr2 = $arr->reverse();
    expect($arr2->length())->toEqual(7);
    expect($arr2->join())->toEqual("7,6,5,4,3,2,1");
    expect($arr->join())->toEqual("7,6,5,4,3,2,1");
    $arr->push(0);
    expect($arr2->join())->toEqual("7,6,5,4,3,2,1");
    expect($arr->join())->toEqual("7,6,5,4,3,2,1,0");
});

it('sorts Arr', function () {
    $arr = Arr::make([ 6,2,4,2,1,9,7]);
    expect($arr->join())->toEqual("6,2,4,2,1,9,7");
    $arr->sort();
    expect($arr->length())->toEqual(7);
    expect($arr->join())->toEqual("1,2,2,4,6,7,9");
});

it('splices Arr', function () {
    $months = Arr::make(['Jan', 'March', 'April', 'June']);
    $months->splice(1, 0, 'Feb');
    expect($months->join())->toEqual("Jan,Feb,March,April,June");
    $months->splice(4, 1, 'May');
    expect($months->join())->toEqual("Jan,Feb,March,April,May");
    $months->splice(1);
    expect($months->join())->toEqual("Jan");
});

it('stringifies an Arr', function () {
    $months = Arr::make(['Jan', 'Feb', 'March', 'April', 'May']);
    expect($months->toString())->toEqual("Jan,Feb,March,April,May");
});

it('checks is array', function () {
    $isArray = Arr::isArray(['Jan', 'Feb', 'March', 'April', 'May']);
    expect($isArray)->toEqual(true);
    $isArray = Arr::isArray(null);
    expect($isArray)->toEqual(false);
    $isArray = Arr::isArray(1);
    expect($isArray)->toEqual(false);
    $isArray = Arr::isArray(0);
    expect($isArray)->toEqual(false);
});

it('implements of() method', function () {
    $arr = Arr::of('Jan', 'Feb', 'March', 'April', 'May');
    expect($arr->length())->toEqual(5);
    expect($arr[4])->toEqual('May');

    $arr = Arr::of(7);
    expect($arr->length())->toEqual(1);
    expect($arr[0])->toEqual(7);
});

it('tests keys() method', function () {
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
    expect($arrKeys[4])->toEqual("05");

    $keys = $arr->keys();
    expect($keys)->toBeArray();
    expect(count($keys))->toEqual(12);
    expect($keys[4])->toEqual("05");

    foreach ($arrKeys as $key => $value) {
        expect($arrKeys[$key])->toEqual($keys[$key]);
    }
});

it('test keys() method', function () {
    $arr = Arr::make(
        [5, 12, 8, 130, 44]
    );
    expect($arr->length())->toEqual(5);
    expect($arr->at(2))->toEqual(8);
    expect($arr->at(-2))->toEqual(130);
    expect($arr->at(100))->toBeNull();
});
