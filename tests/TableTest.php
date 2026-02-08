<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use HiFolks\DataType\Enums\GroupByKeyMode;
use HiFolks\DataType\Table;
use Iterator;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    private array $dataTable;

    protected function setUp(): void
    {
        $this->dataTable = [
            ["product" => "Desk", "price" => 200, "active" => true],
            ["product" => "Chair", "price" => 100, "active" => true],
            ["product" => "Door", "price" => 300, "active" => false],
            ["product" => "Bookcase", "price" => 150, "active" => true],
            ["product" => "Door", "price" => 100, "active" => true],
        ];
    }

    public function test_first(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertSame(5, $table->count());
        $tableItem = $table->first();
        $this->assertSame("Desk", $tableItem->get("product"));
        $this->assertSame(200, $tableItem->get("price"));
        $this->assertTrue($tableItem->get("active"));

        // var_dump($tableItem);
    }

    public function test_last(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertSame(5, $table->count());
        $tableItem = $table->last();
        $this->assertSame("Door", $tableItem->get("product"));
        $this->assertSame(100, $tableItem->get("price"));
        $this->assertTrue($tableItem->get("active"));

        // var_dump($tableItem);
    }
    public function test_groupby(): void
    {
        $table = Table::make($this->dataTable);

        $tableProducts = $table->groupBy("product");
        $this->assertSame(4, $tableProducts->count());
        $itemsDesk = $tableProducts->get("Desk");
        $this->assertSame(1, $itemsDesk->count());
        $itemsDesk = $tableProducts->get("Door");
        $this->assertSame(2, $itemsDesk->count());
        $item = $itemsDesk->first();
        $this->assertSame(300, $item->get("price"));
        $this->assertSame("Door", $item->get("product"));
        $this->assertFalse($item->get("active"));
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
        $this->assertEquals(
            ["product" => "Door", "price" => 300, "active" => false],
            $table->current()->arr(),
        );
        $table->prev();
        $this->assertInstanceOf(Arr::class, $table->current());
        $this->assertEquals(
            ["product" => "Chair", "price" => 100, "active" => true],
            $table->current()->arr(),
        );
    }

    public function test_can_get_first(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->first());
        $this->assertCount(3, $table->first());
        $this->assertIsArray($table->first()?->keys());
        $this->assertCount(3, $table->first()?->keys());
        $this->assertEquals(
            ["product", "price", "active"],
            $table->first()?->keys(),
        );
    }

    public function test_can_get_column_from_first(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertIsInt($table->getFromFirst("price"));
        $this->assertEquals(200, $table->getFromFirst("price"));
        $this->assertNull($table->getFromFirst("unknown_column"));
    }

    public function test_can_get_last(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertCount(3, $table->last());
        $this->assertIsArray($table->last()?->arr());
        $this->assertEquals(
            ["product" => "Door", "price" => 100, "active" => true],
            $table->last()?->arr(),
        );
        $this->assertIsArray($table->last()?->keys());
        $this->assertCount(3, $table->last()?->keys());
        $this->assertEquals(
            ["product", "price", "active"],
            $table->last()?->keys(),
        );
    }

    public function test_can_get_column_from_last(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertIsInt($table->getFromLast("price"));
        $this->assertEquals(100, $table->getFromLast("price"));
        $this->assertNull($table->getFromLast("unknown_column"));
    }

    public function test_can_select(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(
            Arr::class,
            $table->select("product", "active")->last(),
        );
        $this->assertCount(2, $table->select("product", "active")->last());
        $this->assertEquals(
            ["product", "active"],
            $table->select("product", "active")->last()?->keys(),
        );
        $this->assertTrue($table->first()?->get("active"));
        $firstArr = $table->first()?->arr();
        $this->assertEquals("Desk", $firstArr["product"]);
        $this->assertTrue($firstArr["active"]);
    }

    public function test_can_except(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(Arr::class, $table->except("price")->last());
        $this->assertCount(2, $table->except("price")->last());
        $this->assertEquals(
            ["product", "active"],
            $table->except("price")->last()?->keys(),
        );
        $this->assertTrue($table->first()?->get("active"));
        $firstArr = $table->first()?->arr();
        $this->assertEquals("Desk", $firstArr["product"]);
        $this->assertTrue($firstArr["active"]);
        $this->assertCount(5, $table->rows());

        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(
            Arr::class,
            $table->except("field_not_exist")->last(),
        );
        $this->assertCount(3, $table->except("field_not_exist")->last());
        $this->assertEquals(
            ["product", "price", "active"],
            $table->except("field_not_exists")->last()?->keys(),
        );
        $this->assertCount(5, $table->rows());
    }

    public function test_can_filter(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertIsArray($table->rows());
        $this->assertInstanceOf(
            Arr::class,
            $table->select("product", "price")->last(),
        );
        $this->assertCount(2, $table->select("product", "price")->last());
        $this->assertCount(
            2,
            $table->select("product", "price")->where("price", 100)->last(),
        );
        $this->assertCount(
            2,
            $table->select("product", "price")->where("price", 100),
        );
        $this->assertEquals(
            ["product", "price"],
            $table
                ->select("product", "price")
                ->where("price", 100)
                ->last()
                ?->keys(),
        );
        $this->assertEquals(
            ["product", "price"],
            $table
                ->select("product", "price")
                ->where("price", "IDONTKNOW", 100)
                ->last()
                ?->keys(),
        );
        $this->assertEquals(
            200,
            $table
                ->select("product", "price")
                ->where("price", "IDONTKNOW", 200)
                ->getFromLast("price"),
        );
        $this->assertEquals(
            100,
            $table
                ->select("product", "price")
                ->where("price", "IDONTKNOW", 100)
                ->getFromLast("price"),
        );
    }

    public function test_can_filter_greater_than(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(
            3,
            $table->select("product", "price")->where("price", ">", 100),
        );

        $table = Table::make($this->dataTable);
        $this->assertCount(
            5,
            $table->select("product", "price")->where("price", ">=", 100),
        );
    }

    public function test_can_filter_true(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(
            4,
            $table->where("active")->select("product", "price"),
        );
    }

    public function test_can_filter_smaller(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(
            2,
            $table->select("product", "price")->where("price", "<=", 100),
        );

        $table = Table::make($this->dataTable);
        $this->assertCount(
            0,
            $table->select("product", "price")->where("price", "<", 100),
        );
    }

    public function test_can_filter_not_equal(): void
    {
        $table = Table::make($this->dataTable);
        $this->assertCount(
            3,
            $table->select("product", "price")->where("price", "!=", "100"),
        );

        $table = Table::make($this->dataTable);
        $this->assertCount(
            3,
            $table->select("product", "price")->where("price", "!==", 100),
        );

        $table = Table::make($this->dataTable);
        $this->assertCount(
            5,
            $table->select("product", "price")->where("price", "!==", "100"),
        );
    }

    public function test_can_create_calculated_field(): void
    {
        $table = Table::make($this->dataTable);

        $calculatedTable = $table
            ->select("product", "price")
            ->where("price", ">", 100)
            ->calc("new_field", fn ($item): int|float => $item["price"] * 2);

        $this->assertCount(3, $calculatedTable);
        $this->assertEquals(200, $calculatedTable->first()?->get("price"));
        $this->assertEquals(400, $calculatedTable->first()?->get("new_field"));
        $this->assertEquals(150, $calculatedTable->last()?->get("price"));
        $this->assertEquals(300, $calculatedTable->last()?->get("new_field"));
    }

    public function test_can_group(): void
    {
        $table = Table::make($this->dataTable);
        $groupedTable = $table->groupBy("product");

        // 4 distinct products: Desk, Chair, Door, Bookcase
        $this->assertCount(4, $groupedTable);
        // Each group is a Table
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // Desk group has 1 row
        $this->assertCount(1, $groupedTable->first());
        // Door group (last) has 2 rows
        $this->assertCount(1, $groupedTable->last());
        // Verify the first group's first row
        $firstGroup = $groupedTable->first();
        $this->assertInstanceOf(Table::class, $firstGroup);
        $this->assertEquals(
            ["product" => "Desk", "price" => 200, "active" => true],
            $firstGroup->first()?->arr(),
        );
    }

    public function test_can_group_by_boolean_field(): void
    {
        $table = Table::make($this->dataTable);
        // 'active' has true (4 rows) and false (1 row) — two distinct groups
        $groupedTable = $table->groupBy("active");

        $this->assertCount(2, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // true group has 4 rows
        $this->assertCount(4, $groupedTable->first());
        // false group has 1 row
        $this->assertCount(1, $groupedTable->last());
    }

    public function test_group_by_skips_null_values(): void
    {
        $data = [
            ["name" => "A", "category" => "fruit"],
            ["name" => "B", "category" => null],
            ["name" => "C", "category" => false],
            ["name" => "D", "category" => "fruit"],
            ["name" => "E", "category" => "veggie"],
        ];
        $table = Table::make($data);
        $groupedTable = $table->groupBy("category");

        // null is skipped, false is a valid group => fruit + false + veggie
        $this->assertCount(3, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // fruit group has 2 rows (A and D)
        $this->assertCount(2, $groupedTable->first());
        // veggie group has 1 row (E)
        $this->assertCount(1, $groupedTable->last());
    }

    public function test_group_by_boolean(): void
    {
        $data = [
            ["name" => "A", "status" => true],
            ["name" => "B", "status" => false],
            ["name" => "C", "status" => true],
            ["name" => "D", "status" => true],
            ["name" => "E", "status" => true],
        ];
        $table = Table::make($data);
        $groupedTable = $table->groupBy("status");

        $this->assertCount(2, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // true group has 4 rows (A, C, D, E)
        $this->assertCount(4, $groupedTable->first());
        // false group has 1 row (B)
        $this->assertCount(1, $groupedTable->last());
    }

    public function test_group_by_boolean_mixed(): void
    {
        $data = [
            ["name" => "A", "level" => true],
            ["name" => "B", "level" => false],
            ["name" => "C", "level" => true],
            ["name" => "D", "level" => true],
            ["name" => "E", "level" => true],
            ["name" => "F", "level" => 0],
            ["name" => "G", "level" => 1],
            ["name" => "H", "level" => 2],
        ];
        $table = Table::make($data);
        $groupedTable = $table->groupBy("level");
        // var_export avoids collisions: true="true", 1="1", false="false", 0="0"
        // Groups: true(A,C,D,E), false(B), 0(F), 1(G), 2(H)
        $this->assertCount(5, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // true group has 4 rows (A, C, D, E)
        $this->assertCount(4, $groupedTable->first());
        // 2 group (last) has 1 row (H)
        $this->assertCount(1, $groupedTable->last());
    }

    public function test_group_by_skips_non_scalar_values(): void
    {
        $data = [
            ["name" => "A", "tags" => ["x", "y"]],
            ["name" => "B", "tags" => "single"],
        ];
        $table = Table::make($data);
        $groupedTable = $table->groupBy("tags");

        // array value hits default => null in castVariableForStrval, gets skipped
        $this->assertCount(1, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // single group has 1 row (B)
        $this->assertCount(1, $groupedTable->first());
    }

    public function test_can_group_by_integer_field(): void
    {
        $table = Table::make($this->dataTable);
        // prices: 200, 100, 300, 150, 100 — 100 appears twice, grouped together
        $groupedTable = $table->groupBy("price");

        $this->assertCount(4, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // 200 group has 1 row
        $this->assertCount(1, $groupedTable->first());
        // The 100 group should have 2 rows
        $array = $groupedTable->toArray();
        $this->assertCount(2, $array[100]);
    }

    public function test_can_group_by_float_field(): void
    {
        $data = [
            ["name" => "A", "score" => 1.5],
            ["name" => "B", "score" => 2.5],
            ["name" => "C", "score" => 1.5],
        ];
        $table = Table::make($data);
        $groupedTable = $table->groupBy("score");

        // 1.5 grouped together => 1.5 + 2.5
        $this->assertCount(2, $groupedTable);
        $this->assertInstanceOf(Table::class, $groupedTable->first());
        // 1.5 group has 2 rows (A and C)
        $this->assertCount(2, $groupedTable->first());
        // 2.5 group has 1 row (B)
        $this->assertCount(1, $groupedTable->last());
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
        $orderedTable = $table->orderBy("price");
        $this->assertCount(5, $orderedTable);
        $this->assertEquals(
            ["product" => "Door", "price" => 300, "active" => false],
            $orderedTable->first()?->arr(),
        );
        $this->assertEquals(
            ["product" => "Door", "price" => 100, "active" => true],
            $orderedTable->last()?->arr(),
        );
    }

    public function test_orders_by_asc(): void
    {
        $table = Table::make($this->dataTable);
        $orderedTable = $table->orderBy("product", "asc");
        $this->assertCount(5, $orderedTable);
        $this->assertEquals(
            ["product" => "Bookcase", "price" => 150, "active" => true],
            $orderedTable->first()?->arr(),
        );
        $this->assertEquals(
            ["product" => "Door", "price" => 100, "active" => true],
            $orderedTable->last()?->arr(),
        );
    }

    public function test_can_get_the_cheapest_of_all_products_that_are_active(): void
    {
        $table = Table::make($this->dataTable);
        $cheapestOfEachProduct = $table
            ->where("active", "=", true)
            ->orderBy("price", "asc")
            ->groupBy("product");

        // 4 active products: Chair, Door, Bookcase, Desk (ordered by price asc)
        $this->assertCount(4, $cheapestOfEachProduct);
        $this->assertInstanceOf(Table::class, $cheapestOfEachProduct->first());
        // First group (Chair) — cheapest first row
        $firstGroup = $cheapestOfEachProduct->first();
        $this->assertInstanceOf(Table::class, $firstGroup);
        $this->assertEquals(
            ["product" => "Chair", "price" => 100, "active" => true],
            $firstGroup->first()?->arr(),
        );
        // Last group (Desk)
        $lastGroup = $cheapestOfEachProduct->last();
        $this->assertInstanceOf(Table::class, $lastGroup);
        $this->assertEquals(
            ["product" => "Desk", "price" => 200, "active" => true],
            $lastGroup->first()?->arr(),
        );
    }

    public function test_can_transform_all_of_the_elements_in_a_specific_column(): void
    {
        $table = Table::make($this->dataTable);
        $cheapestOfEachProduct = $table->transform(
            "price",
            fn ($price): string => number_format($price, 2),
        );

        $this->assertCount(5, $cheapestOfEachProduct);
        $this->assertEquals(
            ["product" => "Desk", "price" => "200.00", "active" => true],
            $cheapestOfEachProduct->first()?->arr(),
        );
        $this->assertEquals(
            ["product" => "Door", "price" => "100.00", "active" => true],
            $cheapestOfEachProduct->last()?->arr(),
        );
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

    public function test_get_by_integer_key(): void
    {
        $table = Table::make($this->dataTable);
        $row = $table->get(0);
        $this->assertInstanceOf(Arr::class, $row);
        $this->assertSame("Desk", $row->get("product"));

        $row = $table->get(4);
        $this->assertInstanceOf(Arr::class, $row);
        $this->assertSame("Door", $row->get("product"));

        $this->assertNull($table->get(999));
    }

    public function test_get_by_string_key_on_grouped_table(): void
    {
        $table = Table::make($this->dataTable);
        $grouped = $table->groupBy("product", GroupByKeyMode::Strval);

        $deskGroup = $grouped->get("Desk");
        $this->assertInstanceOf(Table::class, $deskGroup);
        $this->assertCount(1, $deskGroup);

        $doorGroup = $grouped->get("Door");
        $this->assertInstanceOf(Table::class, $doorGroup);
        $this->assertCount(2, $doorGroup);

        $this->assertNull($grouped->get("NonExistent"));
    }

    public function test_to_json(): void
    {
        $table = Table::make($this->dataTable);
        $json = $table->toJson();

        $this->assertIsString($json);
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertCount(5, $decoded);
        $this->assertSame("Desk", $decoded[0]["product"]);
        $this->assertSame(200, $decoded[0]["price"]);
        $this->assertTrue($decoded[0]["active"]);
        $this->assertSame("Door", $decoded[4]["product"]);
    }

    public function test_to_json_empty_table(): void
    {
        $table = Table::make([]);
        $json = $table->toJson();

        $this->assertSame("[]", $json);
    }

    public function test_to_json_with_options(): void
    {
        $table = Table::make([["name" => "Alice"]]);
        $json = $table->toJson(JSON_PRETTY_PRINT);

        $this->assertStringContainsString("\n", $json);
        $this->assertStringContainsString("Alice", $json);
    }

    public function test_to_json_grouped_table(): void
    {
        $table = Table::make($this->dataTable);
        $grouped = $table->groupBy("product", GroupByKeyMode::Strval);
        $json = $grouped->toJson();

        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertCount(4, $decoded);
        $this->assertArrayHasKey("Desk", $decoded);
        $this->assertArrayHasKey("Door", $decoded);
        $this->assertCount(2, $decoded["Door"]);
        $this->assertCount(1, $decoded["Desk"]);
    }

    public function test_group_by_with_strval_key_mode(): void
    {
        $table = Table::make($this->dataTable);
        $groupedTable = $table->groupBy("product", GroupByKeyMode::Strval);

        $this->assertCount(4, $groupedTable);
        // Keys should be plain strings (strval), not var_export quoted strings
        $keys = array_keys($groupedTable->toArray());
        $this->assertSame(["Desk", "Chair", "Door", "Bookcase"], $keys);
    }

    public function test_group_by_with_int_key_mode(): void
    {
        $table = Table::make($this->dataTable);
        $groupedTable = $table->groupBy("price", GroupByKeyMode::Int);

        // prices: 200, 100, 300, 150, 100 — 4 distinct integer keys
        $this->assertCount(4, $groupedTable);
        $keys = array_keys($groupedTable->toArray());
        $this->assertSame([200, 100, 300, 150], $keys);
        // The 100 group has 2 rows
        $array = $groupedTable->toArray();
        $this->assertCount(2, $array[100]);
    }

    public function test_group_by_with_var_export_key_mode(): void
    {
        $table = Table::make($this->dataTable);
        $groupedTable = $table->groupBy("product", GroupByKeyMode::VarExport);

        $this->assertCount(4, $groupedTable);
        // Keys should be var_export'd strings (single-quoted)
        $keys = array_keys($groupedTable->toArray());
        $this->assertSame(["Desk", "Chair", "Door", "Bookcase"], $keys);
    }

    public function test_group_by_strval_boolean_collision(): void
    {
        $data = [
            ["name" => "A", "level" => true],
            ["name" => "B", "level" => 1],
            ["name" => "C", "level" => false],
            ["name" => "D", "level" => 0],
        ];
        $table = Table::make($data);

        // Strval: true->"1", 1->"1" collide; false->"", 0->"0" do not — 3 groups
        $groupedTable = $table->groupBy("level", GroupByKeyMode::Strval);
        $this->assertCount(3, $groupedTable);
        // "1" group has 2 rows (A and B)
        $this->assertCount(2, $groupedTable->first());

        // VarExport: true="true", 1="1", false="false", 0="0" — 4 groups, no collision
        $groupedTable = $table->groupBy("level", GroupByKeyMode::VarExport);
        $this->assertCount(4, $groupedTable);
    }

    public function test_group_by_int_key_mode_with_floats(): void
    {
        $data = [
            ["name" => "A", "score" => 1.5],
            ["name" => "B", "score" => 2.5],
            ["name" => "C", "score" => 1.9],
        ];
        $table = Table::make($data);
        $groupedTable = $table->groupBy("score", GroupByKeyMode::Int);

        // intval: 1.5->1, 2.5->2, 1.9->1 — 2 groups
        $this->assertCount(2, $groupedTable);
        $keys = array_keys($groupedTable->toArray());
        $this->assertSame([1, 2], $keys);
        // int 1 group has 2 rows (A and C)
        $this->assertCount(2, $groupedTable->first());
    }
}
