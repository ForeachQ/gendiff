<?php

namespace Differ\Tests\Parsers;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\Parsers\Factory\ParserFactory\getParser;

class ParserFactoryTest extends TestCase
{
    private const FILE1_EXPECTED = [
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
    public function testJson(): void
    {
        $path = dirname(__DIR__) . "/fixtures/file1.json";
        $parser = getParser($path);
        $actual = $parser($path);

        $this->assertEquals(self::FILE1_EXPECTED, $actual);
    }

    public function testYaml(): void
    {
        $path = dirname(__DIR__) . "/fixtures/file1.yaml";
        $parser = getParser($path);
        $actual = $parser($path);

        $this->assertEquals(self::FILE1_EXPECTED, $actual);
    }

    public function testWrongExtension(): void
    {
        $path = dirname(__DIR__) . "/fixtures/wrong.jon";
        $this->expectException(Exception::class);
        $parser = getParser($path);
        $parser($path);
    }

    public function testWrongYamlPath(): void
    {
        $path = dirname(__DIR__) . "/fixtures/wrong.yaml";
        $this->expectException(Exception::class);
        $parser = getParser($path);
        $parser($path);
    }
}
