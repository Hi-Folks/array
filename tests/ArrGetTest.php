<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class ArrGetTest extends TestCase
{
    public function test_basic_get(): void
    {
        $arr = Arr::make(['A', 'B', 'C']);
        $this->assertSame('B', $arr->get(1));
        $this->assertNull($arr->get(4));
        $this->assertSame("AAAA", $arr->get(4, 'AAAA'));
    }

    public function test_basic_nested_get(): void
    {
        $arr = Arr::make([
            'A' => 'First',
            'B' => ['some', 'thing'],
            'C' => ['nested-item-1' => 10, 'nested-item-2' => 20],
            'D' => []
        ]);
        $this->assertSame('First', $arr->get('A'));
        $this->assertIsArray($arr->get('B'));
        $this->assertSame('some', $arr->get('B.0'));
        $this->assertSame('thing', $arr->get('B.1'));
        $this->assertNull($arr->get('B.2'));
        $this->assertSame(1234, $arr->get('B.2', 1234));
        $this->assertSame('some', $arr->get('B#0', charNestedKey: '#'));
        $this->assertSame('thing', $arr->get('B#1', charNestedKey: '#'));
        $this->assertNull($arr->get('B#2', charNestedKey: '#'));
        $this->assertSame(1234, $arr->get('B#2', 1234, '#'));

        $this->assertNull($arr->get('C.0'));
        $this->assertSame(10, $arr->get('C.nested-item-1'));
        $this->assertSame(20, $arr->get('C.nested-item-2'));
        $this->assertNull($arr->get('C.nested-item-2.other'));
        $this->assertSame('zzz', $arr->get('C.nested-item-2.other', 'zzz'));
        $this->assertSame('zzz', $arr->get('C#nested-item-2#other', 'zzz', '#'));
        $this->assertSame('zzz', $arr->get('C#nested-item-2#other', 'zzz'));
        $this->assertSame('zzz', $arr->get('C#nested-item-2', 'zzz'));
        $this->assertSame('zzz', $arr->get('C#nested-item-4', 'zzz'));
        $this->assertSame('zzz', $arr->get('D#nested-item-4', 'zzz'));
        $this->assertNull($arr->get('D.0'));
        $result = $arr->get('D', '0');
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    public function test_basic_get_arr(): void
    {
        $arr = Arr::make(['A', 'B', 'C']);
        $this->assertInstanceOf(Arr::class, $arr->getArr(1));
        $this->assertSame('B', $arr->getArr(1)->at(0));
        $this->assertNull($arr->getArrNullable(4));
        $this->assertInstanceOf(Arr::class, $arr->getArr(4, 'AAAA'));
        $this->assertSame('AAAA', $arr->getArr(4, 'AAAA')->get(0));

        $arr = Arr::make([
            'A' => 'First',
            'B' => ['some', 'thing'],
            'C' => ['nested-item-1' => 10, 'nested-item-2' => 20],
            'D' => []
        ]);

        $this->assertSame(10, $arr->getArr('C')->get('nested-item-1'));
        $this->assertIsArray($arr->getArr('C')->entries()->get(0));
        $this->assertSame(['nested-item-1', 10], $arr->getArr('C')->entries()->get(0));
        $this->assertSame(['nested-item-1', 'nested-item-2'], $arr->getArr('C')->keys());

        $arr = Arr::make(
            [
                "avocado" =>
                    [
                        'name' => 'Avocado',
                        'fruit' => '🥑',
                        'wikipedia' => 'https://en.wikipedia.org/wiki/Avocado'
                    ],
                "apple" =>
                    [
                        'name' => 'Apple',
                        'fruit' => '🍎',
                        'wikipedia' => 'https://en.wikipedia.org/wiki/Apple'
                    ],
                "banana" =>
                    [
                        'name' => 'Banana',
                        'fruit' => '🍌',
                        'wikipedia' => 'https://en.wikipedia.org/wiki/Banana'
                    ],
                "cherry" =>
                    [
                        'name' => 'Cherry',
                        'fruit' => '🍒',
                        'wikipedia' => 'https://en.wikipedia.org/wiki/Cherry'
                    ],
            ]
        );
        $appleArr = $arr->getArr("apple");
        $this->assertSame(3, $appleArr->count());
        $this->assertSame('Apple', $appleArr->get("name"));
        $appleNameArr = $arr->getArr("apple.name");
        $this->assertSame('Apple', $appleNameArr->get(0));
        $appleNoExists = $arr->getArr("apple.name.noexists");
        $this->assertInstanceOf(Arr::class, $appleNoExists);
        $this->assertSame(0, $appleNoExists->count());
        $appleNoExists = $arr->getArrNullable("apple.name.noexists");
        $this->assertNull($appleNoExists);
        $appleNoExists = $arr->getArr("apple.name.noexists", "some");
        $this->assertInstanceOf(Arr::class, $appleNoExists);
        $this->assertSame("some", $appleNoExists->get(0));
    }

    public function test_get_arr_nullable_returns_arr_instance_when_value_is_arr(): void
    {
        $inner = Arr::make([10, 20]);
        $arr = Arr::make(['nested' => $inner]);

        $result = $arr->getArrNullable('nested');
        $this->assertInstanceOf(Arr::class, $result);
        $this->assertSame(10, $result->get(0));
        $this->assertSame(20, $result->get(1));
    }

    public function test_get_arr_nullable_returns_empty_arr_for_object_value(): void
    {
        $obj = new \stdClass();
        $obj->name = 'test';
        $arr = Arr::make(['item' => $obj]);

        $result = $arr->getArrNullable('item');
        $this->assertInstanceOf(Arr::class, $result);
        $this->assertSame(0, $result->count());
    }
}
