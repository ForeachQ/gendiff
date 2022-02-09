<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testTwoFilled(): void
    {
        $json1 = "tests/fixtures/file1.json";
        $json2 = "tests/fixtures/file2.json";
        $expected = <<<STR
        {
          - follow: false
            host: hexlet.io
          - proxy: 123.234.53.22
          - timeout: 50
          + timeout: 20
          + verbose: true
        }
        
        STR;
        $actual = genDiff($json1, $json2);
        $this->assertEquals($expected, $actual);
    }

    public function testEmptyAndFilled(): void
    {
        $json1 = "tests/fixtures/file1.json";
        $json2 = "tests/fixtures/empty.json";
        $expected = <<<STR
        {
          - follow: false
          - host: hexlet.io
          - proxy: 123.234.53.22
          - timeout: 50
        }
        
        STR;
        $actual = genDiff($json1, $json2);
        $this->assertEquals($expected, $actual);
    }

    public function testTwoEmpty(): void
    {
        $json1 = "tests/fixtures/empty.json";
        $json2 = "tests/fixtures/empty.json";
        $expected = <<<STR
        {
        }
        
        STR;
        $actual = genDiff($json1, $json2);
        $this->assertEquals($expected, $actual);
    }
}
