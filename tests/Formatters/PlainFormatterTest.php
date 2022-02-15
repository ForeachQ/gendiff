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

        $actual = format($diffs);
        $this->assertEquals($expected, $actual);
    }
}
