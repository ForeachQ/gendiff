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
            ['mod' => 'unchanged', 'key' => 'common', 'val' => [
                ['mod' => 'unchanged', 'key' => 'setting1', 'val' => 'Value 1'],
                ['mod' => 'removed', 'key' => 'setting2', 'val' => '200'],
                ['mod' => 'changed', 'key' => 'setting3', 'val' => ['1', false]],
                ['mod' => 'unchanged', 'key' => 'setting6', 'val' => [
                    ['mod' => 'unchanged', 'key' => 'key', 'val' => 'value'],
                    ['mod' => 'unchanged', 'key' => 'doge', 'val' => [
                        ['mod' => 'changed', 'key' => 'wow', 'val' => [false, 'so much']]
                    ]],
                    ['mod' => 'add', 'key' => 'ops', 'val' => 'vops']
                ]],
                ['mod' => 'add', 'key' => 'follow', 'val' => false],
                ['mod' => 'add', 'key' => 'setting4', 'val' => 'blah blah'],
                ['mod' => 'add', 'key' => 'setting5', 'val' => [
                    ['mod' => 'unchanged', 'key' => 'key5', 'val' => 'value5'],
                ]],
            ]]
        ];

        $this->assertEquals($expected, format($diffs));
    }
}
