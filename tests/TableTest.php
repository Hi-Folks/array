<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use HiFolks\DataType\Table;
use Iterator;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
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

    public function test_is_table(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->first());
    }

    public function test_is_table_countable(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(5, $table);
    }

    public function test_is_iterable(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertInstanceOf(Iterator::class, $table);
        foreach ($table as $row) {
            $this->assertInstanceOf(Arr::class, $row);
        }

        $table->rewind();
        $table->next();
        $table->next();
        $this->assertInstanceOf(Arr::class, $table->current());
        $this->assertEquals(['product' => 'Door', 'price' => 300, 'active' => false], $table->current()->arr());
        $table->prev();
        $this->assertInstanceOf(Arr::class, $table->current());
        $this->assertEquals(['product' => 'Chair', 'price' => 100, 'active' => true], $table->current()->arr());
    }

    public function test_can_get_first(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->first());
        $this->assertCount(3, $table->first());
        $this->assertIsArray($table->first()?->keys());
        $this->assertCount(3, $table->first()?->keys());
        $this->assertEquals(['product', 'price', 'active'], $table->first()?->keys());
    }

    public function test_can_get_column_from_first(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertIsInt($table->getFromFirst('price'));
        $this->assertEquals(200, $table->getFromFirst('price'));
        $this->assertNull($table->getFromFirst('unknown_column'));
    }

    public function test_can_get_last(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertCount(3, $table->last());
        $this->assertIsArray($table->last()?->arr());
        $this->assertEquals(['product' => 'Door', 'price' => 100, 'active' => true], $table->last()?->arr());
        $this->assertIsArray($table->last()?->keys());
        $this->assertCount(3, $table->last()?->keys());
        $this->assertEquals(['product', 'price', 'active'], $table->last()?->keys());
    }

    public function test_can_get_column_from_last(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertIsInt($table->getFromLast('price'));
        $this->assertEquals(100, $table->getFromLast('price'));
        $this->assertNull($table->getFromLast('unknown_column'));
    }

    public function test_can_select(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->select('product', 'active')->last());
        $this->assertCount(2, $table->select('product', 'active')->last());
        $this->assertEquals(['product', 'active'], $table->select('product', 'active')->last()?->keys());
        $this->assertTrue($table->first()?->get('active'));
        $firstArr = $table->first()?->arr();
        $this->assertEquals('Desk', $firstArr['product']);
        $this->assertTrue($firstArr['active']);
    }

    public function test_can_except(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->except('price')->last());
        $this->assertCount(2, $table->except('price')->last());
        $this->assertEquals(['product', 'active'], $table->except('price')->last()?->keys());
        $this->assertTrue($table->first()?->get('active'));
        $firstArr = $table->first()?->arr();
        $this->assertEquals('Desk', $firstArr['product']);
        $this->assertTrue($firstArr['active']);
        $this->assertCount(5, $table->rows());

        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->except('field_not_exist')->last());
        $this->assertCount(3, $table->except('field_not_exist')->last());
        $this->assertEquals(['product', 'price', 'active'], $table->except('field_not_exists')->last()?->keys());
        $this->assertCount(5, $table->rows());
    }

    public function test_can_filter(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->select('product', 'price')->last());
        $this->assertCount(2, $table->select('product', 'price')->last());
        $this->assertCount(2, $table->select('product', 'price')->where('price', 100)->last());
        $this->assertCount(2, $table->select('product', 'price')->where('price', 100));
        $this->assertEquals(
            ['product', 'price'],
            $table->select('product', 'price')->where('price', 100)->last()?->keys()
        );
        $this->assertEquals(
            ['product', 'price'],
            $table->select('product', 'price')->where('price', "IDONTKNOW", 100)->last()?->keys()
        );
        $this->assertEquals(
            200,
            $table->select('product', 'price')->where('price', "IDONTKNOW", 200)->getFromLast("price")
        );
        $this->assertEquals(
            100,
            $table->select('product', 'price')->where('price', "IDONTKNOW", 100)->getFromLast("price")
        );
    }

    public function test_can_filter_greater_than(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(3, $table->select('product', 'price')->where('price', '>', 100));

        $table = Table::make($this->dataTable);
        $this->assertCount(5, $table->select('product', 'price')->where('price', '>=', 100));
    }

    public function test_can_filter_true(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(4, $table->where('active')->select('product', 'price'));
    }

    public function test_can_filter_smaller(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(2, $table->select('product', 'price')->where('price', '<=', 100));

        $table = Table::make($this->dataTable);
        $this->assertCount(0, $table->select('product', 'price')->where('price', '<', 100));
    }

    public function test_can_filter_not_equal(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(3, $table->select('product', 'price')->where('price', '!=', '100'));

        $table = Table::make($this->dataTable);
        $this->assertCount(3, $table->select('product', 'price')->where('price', '!==', 100));

        $table = Table::make($this->dataTable);
        $this->assertCount(5, $table->select('product', 'price')->where('price', '!==', '100'));
    }

    public function test_can_create_calculated_field(): void
    {
        $table = Table::make($this->dataTable);

        $calculatedTable = $table
            ->select('product', 'price')
            ->where('price', '>', 100)
            ->calc('new_field', fn ($item): int|float => $item['price'] * 2);

        $this->assertCount(3, $calculatedTable);
        $this->assertEquals(200, $calculatedTable->first()?->get('price'));
        $this->assertEquals(400, $calculatedTable->first()?->get('new_field'));
        $this->assertEquals(150, $calculatedTable->last()?->get('price'));
        $this->assertEquals(300, $calculatedTable->last()?->get('new_field'));
    }

    public function test_can_group(): void
    {
        $table = Table::make($this->dataTable);
        $groupedTable = $table->groupBy('product');

        $this->assertCount(4, $groupedTable);
        $this->assertEquals(['product' => 'Desk', 'price' => 200, 'active' => true], $groupedTable->first()?->arr());
        $this->assertEquals(['product' => 'Bookcase', 'price' => 150, 'active' => true], $groupedTable->last()?->arr());
    }

    public function test_can_append_arr(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(5, $table);
        $table->append(Arr::make([]));
        $this->assertCount(6, $table);
    }

    public function test_can_append_array(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(5, $table);
        $table->append([]);
        $this->assertCount(6, $table);
        $this->assertInstanceOf(Arr::class, $table->last());
    }

    public function test_orders_by_desc(): void
    {
        $table = Table::make($this->dataTable);
        $orderedTable = $table->orderBy('price');
        $this->assertCount(5, $orderedTable);
        $this->assertEquals(['product' => 'Door', 'price' => 300, 'active' => false], $orderedTable->first()?->arr());
        $this->assertEquals(['product' => 'Door', 'price' => 100, 'active' => true], $orderedTable->last()?->arr());
    }

    public function test_orders_by_asc(): void
    {
        $table = Table::make($this->dataTable);
        $orderedTable = $table->orderBy('product', 'asc');
        $this->assertCount(5, $orderedTable);
        $this->assertEquals(['product' => 'Bookcase', 'price' => 150, 'active' => true], $orderedTable->first()?->arr());
        $this->assertEquals(['product' => 'Door', 'price' => 100, 'active' => true], $orderedTable->last()?->arr());
    }

    public function test_can_get_the_cheapest_of_all_products_that_are_active(): void
    {
        $table = Table::make($this->dataTable);
        $cheapestOfEachProduct = $table
            ->where('active', '=', true)
            ->orderBy('price', 'asc')
            ->groupBy('product');

        $this->assertCount(4, $cheapestOfEachProduct);
        $this->assertEquals(['product' => 'Chair', 'price' => 100, 'active' => true], $cheapestOfEachProduct->first()?->arr());
        $this->assertEquals(['product' => 'Desk', 'price' => 200, 'active' => true], $cheapestOfEachProduct->last()?->arr());
    }

    public function test_can_transform_all_of_the_elements_in_a_specific_column(): void
    {
        $table = Table::make($this->dataTable);
        $cheapestOfEachProduct = $table->transform('price', fn ($price): string => number_format($price, 2));

        $this->assertCount(5, $cheapestOfEachProduct);
        $this->assertEquals(['product' => 'Desk', 'price' => '200.00', 'active' => true], $cheapestOfEachProduct->first()?->arr());
        $this->assertEquals(['product' => 'Door', 'price' => '100.00', 'active' => true], $cheapestOfEachProduct->last()?->arr());
    }

    public function test_can_transform_to_native_array(): void
    {
        $table = Table::make($this->dataTable);
        $array = $table->toArray();

        $this->assertIsArray($array);
        $this->assertCount(5, $array);
        $this->assertEquals("Chair", $array[1]["product"]);

        $table = Table::make([]);
        $array = $table->toArray();

        $this->assertIsArray($array);
        $this->assertCount(0, $array);
    }
}
