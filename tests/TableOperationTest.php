<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Classes\Operation;
use HiFolks\DataType\Table;
use PHPUnit\Framework\TestCase;

class TableOperationTest extends TestCase
{
    private array $dataTable;

    protected function setUp(): void
    {
        $this->dataTable = [
            ['product' => 'Desk', 'price' => 200, 'active' => true],
            ['product' => 'Chair', 'price' => 100, 'active' => true],
            ['product' => 'Door', 'price' => 300, 'active' => false],
            ['product' => 'Bookcase', 'price' => 150, 'active' => true],
            ['product' => 'Door', 'price' => 100, 'active' => true],
        ];
    }

    public function test_multiply_by_2_a_field(): void
    {
        $table = Table::make($this->dataTable);
        $resultTable = $table->calc("price2", Operation::double("price"));
        $this->assertEquals(200, $table->getFromFirst("price"));
        $this->assertEquals(400, $resultTable->getFromFirst("price2"));
        $this->assertEquals(400, $resultTable->getFromFirst("price2"));
        // warning the calc method changes the original data
        $this->assertEquals(400, $table->first()->get("price2"));
        $this->assertEquals(400, $resultTable->first()->get("price2"));
    }

    public function test_add_value_to_a_field(): void
    {
        $table = Table::make($this->dataTable);
        $resultTable = $table->calc("price2", Operation::add("price", 50));
        $this->assertEquals(200, $table->getFromFirst("price"));
        $this->assertEquals(250, $resultTable->getFromFirst("price2"));
        $this->assertEquals(200, $resultTable->getFromFirst("price"));
        // warning the calc method changes the original data
        $this->assertEquals(250, $table->first()->get("price2"));
        $this->assertEquals(250, $resultTable->first()->get("price2"));
    }
}
