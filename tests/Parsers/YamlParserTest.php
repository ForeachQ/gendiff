<?php

namespace Differ\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Exception\ParseException;

use function Differ\Parsers\YamlParser\parse;

class YamlParserTest extends TestCase
{
    public function testNormal(): void
    {
        $path = dirname(__DIR__) . "/fixtures/file1.yaml";
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
}
