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
            ['key' => 'common', 'state' => 'unchanged', 'value' => [
                ['key' => 'setting1', 'state' => 'unchanged', 'value' => 'Value 1'],
                ['key' => 'setting2', 'state' => 'removed', 'value' => '200'],
                ['key' => 'setting3', 'state' => 'changed', 'oldValue' => '1', 'newValue' => false],
                ['key' => 'setting6', 'state' => 'unchanged', 'value' => [
                    ['key' => 'key', 'state' => 'unchanged', 'value' => 'value'],
                    ['key' => 'doge', 'state' => 'unchanged', 'value' => [
                        ['key' => 'wow', 'state' => 'changed', 'oldValue' => false, 'newValue' => 'so much']
                    ]],
                    ['key' => 'ops', 'state' => 'add', 'value' => 'vops']
                ]],
                ['key' => 'follow', 'state' => 'add', 'value' => false],
                ['key' => 'setting4', 'state' => 'add', 'value' => 'blah blah'],
                ['key' => 'setting5', 'state' => 'add', 'value' => [
                    ['key' => 'key5', 'state' => 'unchanged', 'value' => 'value5'],
                ]],
            ]]
        ];

        $this->assertEquals($expected, format($diffs));
    }
}
