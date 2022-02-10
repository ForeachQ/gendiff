<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testTwoFilled(): void
    {
        $array1 = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];
        $array2 = [
            "timeout" => 20,
            "verbose" => true,
            "host" => "hexlet.io"
        ];
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
        $actual = genDiff($array1, $array2);
        $this->assertEquals($expected, $actual);
    }

    public function testEmptyAndFilled(): void
    {
        $array1 = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];
        $array2 = [];
        $expected = <<<STR
        {
          - follow: false
          - host: hexlet.io
          - proxy: 123.234.53.22
          - timeout: 50
        }
        
        STR;
        $actual = genDiff($array1, $array2);
        $this->assertEquals($expected, $actual);
    }

    public function testTwoEmpty(): void
    {
        $array1 = [];
        $array2 = [];
        $expected = <<<STR
        {
        }
        
        STR;
        $actual = genDiff($array1, $array2);
        $this->assertEquals($expected, $actual);
    }
}
