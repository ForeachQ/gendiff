<?php

namespace Differ\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Exception\ParseException;
use Exception;

use function Differ\Parsers\YamlParser\parse;

class YamlParserTest extends TestCase
{
    public function testNormal(): void
    {
        $path = dirname(__DIR__) . "/fixtures/file1.yaml";
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
        $path = dirname(__DIR__) . "/fixtures/empty.yaml";
        $expected = [];
        $actual = parse($path);

        $this->assertEquals($expected, $actual);
    }

    public function testWrongPath(): void
    {
        $path = dirname(__DIR__) . "/fixtures/wrong.yaml";
        $this->expectException(ParseException::class);
        parse($path);
    }

    public function testCorrupted(): void
    {
        $path = dirname(__DIR__) . "/fixtures/corrupted2.yaml";
        $this->expectException(Exception::class);
        parse($path);
    }
}
