<?php

namespace Differ\Tests\Parsers;

use PHPUnit\Framework\TestCase;

use function Differ\Parsers\JsonParser\parse;

class JsonParserTest extends TestCase
{
    public function testNormal(): void
    {
        $path = dirname(__DIR__) . "/fixtures/file1.json";
        $expected = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
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
        $this->expectError();
        parse($path);
    }
}
