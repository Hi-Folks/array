<?php

use HiFolks\DataType\Table;

$dataTable = [
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
];


it('is Table', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
});

it('can get first', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->first())->toBeArray();
    expect($table->first())->toHaveCount(3);
    expect($table->first())->toHaveKeys(["product", "price", "active"]);
});

it('can get last', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->last())->toBeArray();
    expect($table->last())->toHaveCount(3);
    expect($table->last())->toHaveKeys(["product", "price", "active"]);
});

it('can select', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->select(['product' , 'active'])->last())->toBeArray();
    expect($table->select(['product' , 'active'])->last())->toHaveCount(2);
    expect($table->select(['product' , 'active'])->last())->toHaveKeys(["product","active"]);
});

it('can filter', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->select(['product' , 'price'])->last())->toBeArray();
    expect($table->select(['product' , 'price'])->last())->toHaveCount(2);
    expect($table->select(['product' , 'price'])->where('price', 100)->last())->toHaveCount(2);
    expect($table->select(['product' , 'price'])->where('price', 100))->toHaveCount(2);
    expect($table->select(['product' , 'price'])->where('price', 100)->last())->toHaveKeys(["product","price"]);
});

it('can filter as array', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->select(['product' , 'price'])->where('price', 100)->arr();
    expect($arr)->toHaveCount(2);
    expect($arr)->toHaveKeys([1,4]);
});
it('can filter greater than', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->select(['product' , 'price'])->where('price', ">", 100)->arr();
    expect($arr)->toHaveCount(3);
    expect($arr)->toHaveKeys([0,2,3]);

    $table = Table::make($dataTable);
    $arr = $table->select(['product' , 'price'])->where('price', ">=", 100)->arr();
    expect($arr)->toHaveCount(5);
    expect($arr)->toHaveKeys([0,1,2,3,4]);
});
it('can create calculated field', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table
        ->select(['product' , 'price'])
        ->where('price', ">", 100, )
        ->calc('new_field', fn ($item) => $item['price'] * 2)
        ->arr();
    expect($arr)->toHaveCount(3);
    expect($arr)->toHaveKeys([0,2,3]);
    expect($arr[2]['price'])->toEqual(300);
    expect($arr[2]['new_field'])->toEqual(600);
});
