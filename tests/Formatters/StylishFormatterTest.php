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
            'common' => ['state' => 'unchanged', 'value' => [
                'setting1' => ['state' => 'unchanged', 'value' => 'Value 1'],
                'setting2' => ['state' => 'removed', 'value' => '200'],
                'setting3' => ['state' => 'changed', 'oldValue' => '1', 'newValue' => false],
                'setting6' => ['state' => 'unchanged', 'value' => [
                    'key' => ['state' => 'unchanged', 'value' => 'value'],
                    'doge' => ['state' => 'unchanged', 'value' => [
                        'wow' => ['state' => 'changed', 'oldValue' => false, 'newValue' => 'so much']
                    ]],
                    'ops' => ['state' => 'add', 'value' => 'vops']
                ]],
                'follow' => ['state' => 'add', 'value' => false],
                'setting4' => ['state' => 'add', 'value' => 'blah blah'],
                'setting5' => ['state' => 'add', 'value' => [
                    'key5' => ['state' => 'unchanged', 'value' => 'value5'],
                ]],
            ]]
        ];

        $this->assertEquals($expected, format($diffs));
    }
}
