<?php

namespace HiFolks\Array\Tests;

use HiFolks\DataType\Arr;
use PHPUnit\Framework\TestCase;

class ArrSetTest extends TestCase
{
    public function test_basic_set(): void
    {
        $arr = Arr::make(['A', 'B', 'C']);
        $arr->set(0, 1);
        $this->assertCount(3, $arr);
        $arr->set("some", 1);
        $this->assertCount(4, $arr);
        $arr->set("some.thing", 123);
        $this->assertCount(4, $arr);
        $this->assertSame(123, $arr->get("some.thing"));
        $arr->set("test.key.not.exists", "999");
        $this->assertCount(5, $arr);
        $this->assertCount(1, $arr->get("test"));
        $this->assertCount(1, $arr->get("test.key"));
        $this->assertNull($arr->get("test.XXX"));
        $arr->set("test#key#not#exists", "111", "#");
        $this->assertCount(5, $arr);
        $this->assertCount(1, $arr->get("test"));
        $this->assertCount(1, $arr->get("test.key"));
        $this->assertNull($arr->get("test.XXX"));
    }

    public function test_nested_set_array(): void
    {
        $articleText = "Some words as a sample sentence";
        $textField = Arr::make();
        $textField->set("type", "doc");
        $textField->set("content.0.content.0.text", $articleText);
        $textField->set("content.0.content.0.type", "text");
        $textField->set("content.0.type", "paragraph");

        $this->assertSame($articleText, $textField->arr()["content"][0]["content"][0]["text"]);
        $this->assertCount(1, $textField->getArr("content.0.content.0.text"));
        $this->assertIsString($textField->get("content.0.content.0.text"));

        $textField->set("content.0.content.0.text", "Changing Text");
        $this->assertSame("Changing Text", $textField->arr()["content"][0]["content"][0]["text"]);
        $this->assertCount(1, $textField->getArr("content.0.content.0.text"));
        $this->assertIsString($textField->get("content.0.content.0.text"));
    }
}
