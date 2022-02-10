<?php

namespace Differ\Tests\Parsers;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\Parsers\ParserFactory\parse;

class ParserFactoryTest extends TestCase
{
    public function testJson(): void
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

    public function testYaml(): void
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

    public function testWrongExtension(): void
    {
        $path = dirname(__DIR__) . "/fixtures/wrong.jon";
        $this->expectException(Exception::class);
        parse($path);
    }

    public function testWrongYamlPath(): void
    {
        $path = dirname(__DIR__) . "/fixtures/wrong.yaml";
        $this->expectException(Exception::class);
        parse($path);
    }
}
