<?php

namespace Differ\Tests\Formatters;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\StylishFormatter\format;

class StylishFormatterTest extends TestCase
{
    public function testStylish(): void
    {
        $expected = <<<STR
        {
            common: {
              + follow: false
                setting1: Value 1
              - setting2: 200
              - setting3: 1
              + setting3: false
              + setting4: blah blah
              + setting5: {
                    key5: value5
                }
                setting6: {
                    doge: {
                      - wow: false
                      + wow: so much
                    }
                    key: value
                  + ops: vops
                }
            }
        }
        
        STR;

        $diffs = [
            ['mod' => '', 'key' => 'common', 'val' => [
                ['mod' => '', 'key' => 'setting1', 'val' => 'Value 1'],
                ['mod' => '-', 'key' => 'setting2', 'val' => '200'],
                ['mod' => '-', 'key' => 'setting3', 'val' => '1'],
                ['mod' => '+', 'key' => 'setting3', 'val' => false],
                ['mod' => '', 'key' => 'setting6', 'val' => [
                    ['mod' => '', 'key' => 'key', 'val' => 'value'],
                    ['mod' => '', 'key' => 'doge', 'val' => [
                        ['mod' => '-', 'key' => 'wow', 'val' => false],
                        ['mod' => '+', 'key' => 'wow', 'val' => 'so much']
                    ]],
                    ['mod' => '+', 'key' => 'ops', 'val' => 'vops']
                ]],
                ['mod' => '+', 'key' => 'follow', 'val' => false],
                ['mod' => '+', 'key' => 'setting4', 'val' => 'blah blah'],
                ['mod' => '+', 'key' => 'setting5', 'val' => [
                    ['mod' => '', 'key' => 'key5', 'val' => 'value5'],
                ]],
            ]]
        ];

        $this->assertEquals($expected, format($diffs));
    }
}
