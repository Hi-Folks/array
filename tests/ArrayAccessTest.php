<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class ArrayAccessTest extends TestCase
{
    public function test_access_to_element(): void
    {
        $arr = Arr::make([100, 101, 102]);
        $this->assertEquals(100, $arr[0]);
        $this->assertEquals(101, $arr[1]);
        $this->assertEquals(102, $arr[2]);
    }

    public function test_create_elements(): void
    {
        $arr = Arr::make();
        $arr['test01'] = 'Some';
        $arr['test02'] = 'Thing';
        $this->assertEquals(2, $arr->count());
        $this->assertEquals('Thing', $arr['test02']);
        $this->assertEquals('Some', $arr['test01']);

        $arr = Arr::make();
        $arr[] = 'first value';
        $arr[] = 'second value';
        $this->assertEquals(2, $arr->count());
        $this->assertEquals('first value', $arr[0]);
        $this->assertEquals('second value', $arr[1]);
        $this->assertNull($arr[2]);
        $this->assertEquals($arr->get(1), $arr[1]);
    }

    public function test_isset_and_empty(): void
    {
        $arr = Arr::make();
        $arr['test01'] = 'Some';
        $arr['test02'] = 'Thing';
        $this->assertEquals(2, $arr->count());
        $this->assertTrue(isset($arr['test01']));
        $this->assertFalse(empty($arr['test01']));
        $this->assertFalse(isset($arr['not exists']));
        $this->assertTrue(empty($arr['not exists']));
    }

    public function test_unset(): void
    {
        $arr = Arr::make();
        $arr['test01'] = 'Some';
        $arr['test02'] = 'Thing';
        $arr['test03'] = '!!!';
        $this->assertEquals(3, $arr->count());
        $this->assertTrue(isset($arr['test01']));
        $this->assertFalse(empty($arr['test01']));
        $this->assertTrue(isset($arr['test02']));
        $this->assertFalse(empty($arr['test02']));
        unset($arr['test02']);
        $this->assertEquals(2, $arr->count());
        $this->assertTrue(isset($arr['test01']));
        $this->assertFalse(empty($arr['test01']));
        $this->assertFalse(isset($arr['test02']));
        $this->assertTrue(empty($arr['test02']));
    }
}
