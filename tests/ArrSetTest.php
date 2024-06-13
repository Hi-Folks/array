<?php

use HiFolks\DataType\Arr;

it('Basic set', function (): void {
    $arr = Arr::make(['A', 'B', 'C']);
    expect($arr->set(0, 1));
    expect($arr)->toHaveCount(3);
    expect($arr->set("some", 1));
    expect($arr)->toHaveCount(4);
    $arr->set("some.thing", 123);
    expect($arr)->toHaveCount(4);
    expect($arr->get("some.thing"))->toBe(123);
    $arr->set("test.key.not.exists", "999");
    expect($arr)->toHaveCount(5);
    expect($arr->get("test"))->toHaveCount(1);
    expect($arr->get("test.key"))->toHaveCount(1);
    expect($arr->get("test.XXX"))->toBeNull();
    $arr->set("test#key#not#exists", "111", "#");
    expect($arr)->toHaveCount(5);
    expect($arr->get("test"))->toHaveCount(1);
    expect($arr->get("test.key"))->toHaveCount(1);
    expect($arr->get("test.XXX"))->toBeNull();
    //$arr->set(null,1);
});

it(
    'Nested set array',
    function (): void {
        $articleText = "Some words as a sample sentence";
        $textFieldArray = [
            "type" => "doc",
            "content" => [
                [
                    "content" => [
                        [
                            "text" => $articleText,
                            "type" => "text"
                        ]
                    ],
                    "type" => "paragraph"
                ]
            ]
        ];
        $textField = Arr::make();
        $textField->set("type", "doc");
        $textField->set("content.0.content.0.text", $articleText);
        $textField->set("content.0.content.0.type", "text");
        $textField->set("content.0.type", "paragraph");

        expect($textField->arr()["content"][0]["content"][0]["text"])->toBe($articleText);
        expect($textField->getArr("content.0.content.0.text"))->toHaveCount(1);
        expect($textField->get("content.0.content.0.text"))->toBeString();

        $textField->set("content.0.content.0.text", "Changing Text");
        expect($textField->arr()["content"][0]["content"][0]["text"])->toBe("Changing Text");
        expect($textField->getArr("content.0.content.0.text"))->toHaveCount(1);
        expect($textField->get("content.0.content.0.text"))->toBeString();

    }
);
