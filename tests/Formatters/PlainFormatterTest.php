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
        Property 'common.setting2' was updated. From true to '300'
        Property 'common.setting3' was updated. From '1' to false
        Property 'common.setting4' was added with value: 'blah blah'
        Property 'common.setting5' was added with value: [complex value]
        Property 'common.setting6.doge.wow.key' was removed
        Property 'common.setting6.doge.wow.yes' was added with value: null
        Property 'common.setting6.ops' was added with value: 'vops'
        STR;

        $diffs = [
            ['key' => 'common', 'state' => 'unchanged', 'value' => [
                ['key' => 'setting1', 'state' => 'unchanged', 'value' => 'Value 1'],
                ['key' => 'setting2', 'state' => 'changed', 'oldValue' => true, 'newValue' => '300'],
                ['key' => 'setting3', 'state' => 'changed', 'oldValue' => '1', 'newValue' => false],
                ['key' => 'setting6', 'state' => 'unchanged', 'value' => [
                    ['key' => 'key', 'state' => 'unchanged', 'value' => 'value'],
                    ['key' => 'doge', 'state' => 'unchanged', 'value' => [
                        ['key' => 'wow', 'state' => 'unchanged', 'value' => [
                            ['key' => 'key', 'state' => 'removed', 'value' => true],
                            ['key' => 'yes', 'state' => 'added', 'value' => null]
                        ]]
                    ]],
                    ['key' => 'ops', 'state' => 'added', 'value' => 'vops']
                ]],
                ['key' => 'follow', 'state' => 'added', 'value' => false],
                ['key' => 'setting4', 'state' => 'added', 'value' => 'blah blah'],
                ['key' => 'setting5', 'state' => 'added', 'value' => [
                    ['key' => 'key5', 'state' => 'unchanged', 'value' => 'value5'],
                ]],
            ]]
        ];

        $actual = format($diffs);

        $this->assertEquals($expected, $actual);
    }
}
