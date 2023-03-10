<?php

use HiFolks\DataType\Arr;
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
    expect($table->rows())
        ->toBeArray()
        ->and($table->first())
        ->toBeInstanceOf(Arr::class);
});

it('is Table countable', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table)->toHaveCount(5);
});

it('is iterable', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table)->toBeInstanceOf(Iterator::class);
    foreach ($table as $row) {
        expect($row)->toBeInstanceOf(Arr::class);
    }

    $table->rewind();
    $table->next();
    $table->next();
    expect($table->current())
        ->toBeInstanceOf(Arr::class)
        ->and($table->current()->arr())
        ->toMatchArray(['product' => 'Door', 'price' => 300, 'active' => false]);
    $table->prev();
    expect($table->current())
        ->toBeInstanceOf(Arr::class)
        ->and($table->current()->arr())
        ->toMatchArray(['product' => 'Chair', 'price' => 100, 'active' => true]);
});

it('can get first', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())->toBeArray()
        ->and($table->first())
        ->toBeInstanceOf(Arr::class)
        ->and($table->first())
        ->toHaveCount(3)
        ->and($table->first()?->keys())
        ->toBeArray()
        ->toHaveCount(3)
        ->toMatchArray(['product', 'price', 'active']);
});

it('can get column from first', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())
        ->toBeArray()
        ->and($table->getFromFirst('price'))
        ->toBeInt()
        ->toEqual(200)
        ->and($table->getFromFirst('unknown_column'))
        ->toBeNull();
});

it('can get last', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())->toBeArray()
        ->and($table->last())->toHaveCount(3)
        ->and($table->last()?->arr())
        ->toBeArray()
        ->toMatchArray(['product' => 'Door', 'price' => 100, 'active' => true])
        ->and($table->last()?->keys())
        ->toBeArray()
        ->toHaveCount(3)
        ->toMatchArray(['product', 'price', 'active']);
});

it('can get column from last', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())
        ->toBeArray()
        ->and($table->getFromLast('price'))
        ->toBeInt()
        ->toEqual(100)
        ->and($table->getFromLast('unknown_column'))
        ->toBeNull();
});

it('can select', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())->toBeArray()
        ->and(
            $table->select('product', 'active')
                ->last()
        )
        ->toBeInstanceOf(Arr::class)
        ->and($table->select('product', 'active')->last())
        ->toHaveCount(2)
        ->and($table->select('product', 'active')->last()?->keys())
        ->toMatchArray(['product', 'active'])
        ->and($table->first()?->get('active'))
        ->toBeTrue()
        ->and($table->first()?->arr())
        ->toMatchArray(['product' => 'Desk', 'active' => true]);
});

it('can except', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())->toBeArray()
        ->and($table->except('price')->last())
        ->toBeInstanceOf(Arr::class)
        ->and($table->except('price')->last())
        ->toHaveCount(2)
        ->and($table->except('price')->last()?->keys())
        ->toMatchArray(['product', 'active'])
        ->and($table->first()?->get('active'))
        ->toBeTrue()
        ->and($table->first()?->arr())
        ->toMatchArray(['product' => 'Desk', 'active' => true])
        ->and($table->rows())->toHaveCount(5);

    $table = Table::make($dataTable);
    expect($table->rows())->toBeArray()
        ->and($table->except('field_not_exist')->last())
        ->toBeInstanceOf(Arr::class)
        ->and($table->except('field_not_exist')->last())
        ->toHaveCount(3)
        ->and($table->except('field_not_exists')->last()?->keys())
        ->toMatchArray(['product', 'price', 'active'])
        ->and($table->rows())->toHaveCount(5);
});

it('can filter', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table->rows())->toBeArray()
        ->and($table->select('product', 'price')->last())
        ->toBeInstanceOf(Arr::class)
        ->and($table->select('product', 'price')->last())
        ->toHaveCount(2)
        ->and(
            $table->select('product', 'price')
                ->where('price', 100)
                ->last()
        )->toHaveCount(2)
        ->and(
            $table->select('product', 'price')
                ->where('price', 100)
        )->toHaveCount(2)
        ->and(
            $table->select('product', 'price')
                ->where('price', 100)
                ->last()
                ?->keys()
        )->toMatchArray(['product', 'price'])
        ->and(
            $table->select('product', 'price')
                ->where('price', "IDONTKNOW", 100)
                ->last()
                ?->keys()
        )->toMatchArray(['product', 'price'])
        ->and(
            $table->select('product', 'price')
                ->where('price', "IDONTKNOW", 200)
                ->getFromLast("price")
        )->toEqual(200)
        ->and(
            $table->select('product', 'price')
                ->where('price', "IDONTKNOW", 100)
                ->getFromLast("price")
        )->toEqual(100);
});

it('can filter greater than', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect(
        $table->select('product', 'price')
        ->where('price', '>', 100)
    )->toHaveCount(3);

    $table = Table::make($dataTable);
    expect(
        $table->select('product', 'price')
        ->where('price', '>=', 100)
    )->toHaveCount(5);
});

it('can filter true', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect(
        $table->where('active')
        ->select('product', 'price')
    )->toHaveCount(4);
});

it('can filter smaller', function () use ($dataTable) {
    $table = Table::make($dataTable);

    expect(
        $table->select('product', 'price')
        ->where('price', '<=', 100)
    )->toHaveCount(2);

    $table = Table::make($dataTable);
    expect(
        $table->select('product', 'price')
        ->where('price', '<', 100)
    )->toHaveCount(0);
});

it('can filter not equal', function () use ($dataTable) {
    $table = Table::make($dataTable);

    expect(
        $table->select('product', 'price')
        ->where('price', '!=', '100')
    )->toHaveCount(3);

    $table = Table::make($dataTable);

    expect(
        $table->select('product', 'price')
        ->where('price', '!==', 100)
    )->toHaveCount(3);

    $table = Table::make($dataTable);
    expect(
        $table->select('product', 'price')
        ->where('price', '!==', '100')
    )->toHaveCount(5);
});

it('can create calculated field', function () use ($dataTable) {
    $table = Table::make($dataTable);

    $calculatedTable = $table
        ->select('product', 'price')
        ->where('price', '>', 100)
        ->calc('new_field', fn ($item) => $item['price'] * 2);

    expect($calculatedTable)->toHaveCount(3)
        ->and($calculatedTable->first()?->get('price'))->toEqual(200)
        ->and($calculatedTable->first()?->get('new_field'))->toEqual(400)
        ->and($calculatedTable->last()?->get('price'))->toEqual(150)
        ->and($calculatedTable->last()?->get('new_field'))->toEqual(300);
});

it('can group', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $groupedTable = $table->groupBy('product');

    expect($groupedTable)
        ->toHaveCount(4)
        ->and($groupedTable->first()?->arr())
        ->toMatchArray(['product' => 'Desk', 'price' => 200, 'active' => true])
        ->and($groupedTable->last()?->arr())
        ->toMatchArray(['product' => 'Bookcase', 'price' => 150, 'active' => true]);
});

it('can append Arr', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table)->toHaveCount(5);
    $table->append(Arr::make([]));
    expect($table)->toHaveCount(6);
});

it('can append array', function () use ($dataTable) {
    $table = Table::make($dataTable);
    expect($table)->toHaveCount(5);
    $table->append([]);
    expect($table)
        ->toHaveCount(6)
        ->and($table->last())
        ->toBeInstanceOf(Arr::class);
});


it('orders by desc', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $orderedTable = $table->orderBy('price');
    expect($orderedTable)
        ->toHaveCount(5)
        ->and($orderedTable->first()?->arr())
        ->toMatchArray(['product' => 'Door', 'price' => 300, 'active' => false])
        ->and($orderedTable->last()?->arr())
        ->toMatchArray(['product' => 'Door', 'price' => 100, 'active' => true]);
});

it('orders by asc', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $orderedTable = $table->orderBy('product', 'asc');
    expect($orderedTable)
        ->toHaveCount(5)
        ->and($orderedTable->first()?->arr())
        ->toMatchArray(['product' => 'Bookcase', 'price' => 150, 'active' => true])
        ->and($orderedTable->last()?->arr())
        ->toMatchArray(['product' => 'Door', 'price' => 100, 'active' => true]);
});

it('can get the cheapest of all products that are active', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $cheapestOfEachProduct = $table
        ->where('active', '=', true)
        ->orderBy('price', 'asc')
        ->groupBy('product');

    expect($cheapestOfEachProduct)
        ->toHaveCount(4)
        ->and($cheapestOfEachProduct->first()?->arr())
        ->toMatchArray(['product' => 'Chair', 'price' => 100, 'active' => true])
        ->and($cheapestOfEachProduct->last()?->arr())
        ->toMatchArray(['product' => 'Desk', 'price' => 200, 'active' => true]);
});

it('can transform all of the elements in a specific column', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $cheapestOfEachProduct = $table->transform('price', fn ($price) => number_format($price, 2));

    expect($cheapestOfEachProduct)
        ->toHaveCount(5)
        ->and($cheapestOfEachProduct->first()?->arr())
        ->toMatchArray(['product' => 'Desk', 'price' => '200.00', 'active' => true])
        ->and($cheapestOfEachProduct->last()?->arr())
        ->toMatchArray(['product' => 'Door', 'price' => '100.00', 'active' => true]);
});

it('can transform to native array', function () use ($dataTable) {
    $table = Table::make($dataTable);
    $array = $table->toArray();

    expect($array)
        ->toBeArray()
        ->toHaveCount(5);
    expect($array[1]["product"])->toEqual("Chair");

    $table = Table::make([]);
    $array = $table->toArray();

    expect($array)
        ->toBeArray()
        ->toHaveCount(0);
});
