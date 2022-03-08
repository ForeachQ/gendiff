<?php

namespace Differ\Tests\Parsers;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\Parsers\JsonParser\parse;

class JsonParserTest extends TestCase
{
    public function testNormal(): void
    {
        $path = dirname(__DIR__) . "/fixtures/file1.json";
        $expected = [
        "common" => [
            "setting1" => "Value 1",
            "setting2" => 200,
            "setting3" => true,
            "setting6" => [
                "key" => "value",
                "doge" => [
                    "wow" => ""
                ]
            ]
        ],
        "group1" => [
            "baz" => "bas",
            "foo" => "bar",
            "nest" => [
                "key" => "value"
            ]
        ],
        "group2" => [
            "abc" => 12345,
            "deep" => [
                "id" => 45
            ]
        ]
        ];

        $actual = parse($path);

        $this->assertEquals($expected, $actual);
    }

    public function testEmpty(): void
    {
        $path = dirname(__DIR__) . "/fixtures/empty.json";
        $expected = [];
        $actual = parse($path);

        $this->assertEquals($expected, $actual);
    }

    public function testWrongPath(): void
    {
        $path = dirname(__DIR__) . "/fixtures/wrong.json";
        $this->expectException(Exception::class);
        parse($path);
    }

    public function testCorrupted(): void
    {
        $path = dirname(__DIR__) . "/fixtures/corrupted1.json";
        $this->expectException(Exception::class);
        parse($path);
    }
}
