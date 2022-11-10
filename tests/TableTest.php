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
    expect($table->first())->toHaveKeys(['product', 'price', 'active']);
});

it('can get last', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->last())->toBeArray();
    expect($table->last())->toHaveCount(3);
    expect($table->last())->toHaveKeys(['product', 'price', 'active']);
});

it('can select', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->select(['product', 'active'])->last())->toBeArray();
    expect($table->select(['product', 'active'])->last())->toHaveCount(2);
    expect($table->select(['product', 'active'])->last())->toHaveKeys(['product', 'active']);
});

it('can except', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->except(['price'])->last())->toBeArray();
    expect($table->except(['price'])->last())->toHaveCount(2);
    expect($table->except(['price'])->last())->toHaveKeys(['product', 'active']);

    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->except(['field_not_exist'])->last())->toBeArray();
    expect($table->except(['field_not_exist'])->last())->toHaveCount(3);
    expect($table->except(['field_not_exists'])->last())->toHaveKeys(['product', 'active', 'price']);
});

it('can filter', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toBeArray();
    expect($table->select(['product', 'price'])->last())->toBeArray();
    expect($table->select(['product', 'price'])->last())->toHaveCount(2);
    expect($table->select(['product', 'price'])->where('price', 100)->last())->toHaveCount(2);
    expect($table->select(['product', 'price'])->where('price', 100))->toHaveCount(2);
    expect($table->select(['product', 'price'])->where('price', 100)->last())->toHaveKeys(['product', 'price']);
    expect($table->select(['product', 'price'])->where('price', "IDONTKNOW", 100)->last())->toHaveKeys(['product', 'price']);
    expect($table->select(['product', 'price'])->where('price', "IDONTKNOW", 200)->getFromLast("price"))->toEqual(200);
    expect($table->select(['product', 'price'])->where('price', "IDONTKNOW", 100)->getFromLast("price"))->toEqual(100);
});

it('can filter as array', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', 100)->arr();
    expect($arr)->toHaveCount(2);
    expect($arr)->toHaveKeys([1, 4]);
});
it('can filter greater than', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '>', 100)->arr();
    expect($arr)->toHaveCount(3);
    expect($arr)->toHaveKeys([0, 2, 3]);

    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '>=', 100)->arr();
    expect($arr)->toHaveCount(5);
    expect($arr)->toHaveKeys([0, 1, 2, 3, 4]);
});
it('can filter true', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->where('active')->select(['product', 'price'])->arr();
    expect($arr)->toHaveCount(4);
    expect($arr)->toHaveKeys([0, 1, 3, 4]);
});
it('can filter smaller', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '<=', 100)->arr();
    expect($arr)->toHaveCount(2);
    expect($arr)->toHaveKeys([1, 4]);

    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '<', 100)->arr();
    expect($arr)->toHaveCount(0);
});
it('can filter not equal', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '!=', '100')->arr();
    expect($arr)->toHaveCount(3);
    expect($arr)->toHaveKeys([0, 2, 3]);

    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '!==', 100)->arr();
    expect($arr)->toHaveCount(3);
    expect($arr)->toHaveKeys([0, 2, 3]);

    $table = Table::make($dataTable);
    $arr = $table->select(['product', 'price'])->where('price', '!==', '100')->arr();
    expect($arr)->toHaveCount(5);
});

it('can create calculated field', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table
        ->select(['product', 'price'])
        ->where('price', '>', 100)
        ->calc('new_field', fn ($item) => $item['price'] * 2)
        ->arr();
    expect($arr)->toHaveCount(3);
    expect($arr)->toHaveKeys([0, 2, 3]);
    expect($arr[2]['price'])->toEqual(300);
    expect($arr[2]['new_field'])->toEqual(600);
});

it('can group', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $arr = $table->groupBy('product');

    expect($arr)->toHaveCount(4);
    expect($arr->arr())->toMatchArray([
        ['product' => 'Desk', 'price' => 200, 'active' => true],
        ['product' => 'Chair', 'price' => 100, 'active' => true],
        ['product' => 'Door', 'price' => 300, 'active' => false],
        ['product' => 'Bookcase', 'price' => 150, 'active' => true]
    ]);
    expect($arr->count())->toEqual(4);
});

it('insert', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toHaveCount(5);
    $table->insert([
        ["product" => "Door", "price" => 5],
        ["product" => "Door", "price" => 6],
    ]);
    expect($table->arr())->toHaveCount(7);
});

it('get from last', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toHaveCount(5);
    $table->insert([
        ["product" => "Door", "price" => 5],
        ["product" => "Door", "price" => 6],
    ]);
    expect($table->arr())->toHaveCount(7);
    expect($table->getFromLast("price"))->toEqual(6);
});

it('get from first', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->arr())->toHaveCount(5);
    $table->insert([
        ["product" => "Door", "price" => 5],
        ["product" => "Door", "price" => 6],
    ]);
    expect($table->arr())->toHaveCount(7);
    expect($table->getFromFirst("price"))->toEqual(200);
});

it('orders by desc', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $orderedTable = $table->orderBy('price');
    expect($table->arr())->toHaveCount(5);
    expect($orderedTable->arr())->toMatchArray(
        [
            ['product' => 'Door', 'price' => 300, 'active' => false],
            ['product' => 'Desk', 'price' => 200, 'active' => true],
            ['product' => 'Bookcase', 'price' => 150, 'active' => true],
            ['product' => 'Chair', 'price' => 100, 'active' => true],
            ['product' => 'Door', 'price' => 100, 'active' => true],
        ]
    );
});

it('orders by asc', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $orderedTable = $table->orderBy('product', 'asc');
    expect($table->arr())->toHaveCount(5);
    expect($orderedTable->arr())->toMatchArray(
        [
            ['product' => 'Bookcase', 'price' => 150, 'active' => true],
            ['product' => 'Chair', 'price' => 100, 'active' => true],
            ['product' => 'Desk', 'price' => 200, 'active' => true],
            ['product' => 'Door', 'price' => 300, 'active' => false],
            ['product' => 'Door', 'price' => 100, 'active' => true],
        ]
    );
});

it('can get the cheapest of all products that are active', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $cheapestOfEachProduct = $table
        ->where('active', '=', true)
        ->orderBy('price', 'asc')
        ->groupBy('product');

    expect($cheapestOfEachProduct->arr())->toMatchArray([
        ['product' => 'Chair', 'price' => 100, 'active' => true],
        ['product' => 'Door', 'price' => 100, 'active' => true],
        ['product' => 'Bookcase', 'price' => 150, 'active' => true],
        ['product' => 'Desk', 'price' => 200, 'active' => true],
    ]);
});

it('can transform all of the elements in a specific column', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $cheapestOfEachProduct = $table->transform('price', function ($price) {
        return number_format($price, 2);
    });

    expect($cheapestOfEachProduct->arr())->toMatchArray([
        ['product' => 'Desk', 'price' => '200.00', 'active' => true],
        ['product' => 'Chair', 'price' => '100.00', 'active' => true],
        ['product' => 'Door', 'price' => '300.00', 'active' => false],
        ['product' => 'Bookcase', 'price' => '150.00', 'active' => true],
        ['product' => 'Door', 'price' => '100.00', 'active' => true],
    ]);
});
