<?php

use HiFolks\DataType\Arr;

it('access to element', function () {
    $arr = Arr::make([100,101,102]);
    expect($arr[0])->toEqual(100);
    expect($arr[1])->toEqual(101);
    expect($arr[2])->toEqual(102);
});

it('create elements', function () {
    $arr = Arr::make();
    $arr["test01"] = "Some";
    $arr["test02"] = "Thing";
    expect($arr->count())->toEqual(2);
    expect($arr["test02"])->toEqual("Thing");
    expect($arr["test01"])->toEqual("Some");

    $arr = Arr::make();
    $arr[] = "first value";
    $arr[] = "second value";
    expect($arr->count())->toEqual(2);
    expect($arr[0])->toEqual("first value");
    expect($arr[1])->toEqual("second value");
    expect($arr[2])->toBeNull();
    expect($arr[1])->toEqual($arr->get(1));
});
it('isset and empty', function () {
    $arr = Arr::make();
    $arr["test01"] = "Some";
    $arr["test02"] = "Thing";
    expect($arr->count())->toEqual(2);
    expect(isset($arr["test01"]))->toBeTrue();
    expect(empty($arr["test01"]))->toBeFalse();
    expect(isset($arr["not exists"]))->toBeFalse();
    expect(empty($arr["not exists"]))->toBeTrue();
});

it('unset', function () {
    $arr = Arr::make();
    $arr["test01"] = "Some";
    $arr["test02"] = "Thing";
    $arr["test03"] = "!!!";
    expect($arr->count())->toEqual(3);
    expect(isset($arr["test01"]))->toBeTrue();
    expect(empty($arr["test01"]))->toBeFalse();
    expect(isset($arr["test02"]))->toBeTrue();
    expect(empty($arr["test02"]))->toBeFalse();
    unset($arr["test02"]);
    expect($arr->count())->toEqual(2);
    expect(isset($arr["test01"]))->toBeTrue();
    expect(empty($arr["test01"]))->toBeFalse();
    expect(isset($arr["test02"]))->toBeFalse();
    expect(empty($arr["test02"]))->toBeTrue();
});
