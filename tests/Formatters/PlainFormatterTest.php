<?php

namespace Differ\Tests\Formatters;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\PlainFormatter\format;

class PlainFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $expected = <<<STR
        Property 'common.follow' was added with value: false
        Property 'common.setting2' was removed
        Property 'common.setting3' was updated. From '1' to false
        Property 'common.setting4' was added with value: 'blah blah'
        Property 'common.setting5' was added with value: [complex value]
        Property 'common.setting6.doge.wow' was updated. From false to 'so much'
        Property 'common.setting6.ops' was added with value: 'vops'
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

        $actual = format($diffs);
        $this->assertEquals($expected, $actual);
    }
}
