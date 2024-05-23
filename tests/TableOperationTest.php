<?php

use HiFolks\DataType\Classes\Operation;
use HiFolks\DataType\Table;

$dataTable = [
    ['product' => 'Desk', 'price' => 200, 'active' => true],
    ['product' => 'Chair', 'price' => 100, 'active' => true],
    ['product' => 'Door', 'price' => 300, 'active' => false],
    ['product' => 'Bookcase', 'price' => 150, 'active' => true],
    ['product' => 'Door', 'price' => 100, 'active' => true],
];

it('multiply by 2 a field', function () use ($dataTable): void {
    $table = Table::make($dataTable);
    $resultTable = $table->calc("price2", Operation::double("price"));
    expect($table->getFromFirst("price"))->toEqual(200);
    expect($resultTable->getFromFirst("price2"))->toEqual(400);
    expect($resultTable->getFromFirst("price2"))->toEqual(400);
    // warning the calc method changes the original data
    expect($table->first()->get("price2"))->toEqual(400);
    expect($resultTable->first()->get("price2"))->toEqual(400);
});
it('add value to a field', function () use ($dataTable): void {
    $table = Table::make($dataTable);
    $resultTable = $table->calc("price2", Operation::add("price", 50));
    expect($table->getFromFirst("price"))->toEqual(200);
    expect($resultTable->getFromFirst("price2"))->toEqual(250);
    expect($resultTable->getFromFirst("price"))->toEqual(200);
    // warning the calc method changes the original data
    expect($table->first()->get("price2"))->toEqual(250);
    expect($resultTable->first()->get("price2"))->toEqual(250);
});
