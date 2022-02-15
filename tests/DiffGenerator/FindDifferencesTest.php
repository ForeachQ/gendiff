<?php

namespace Differ\Tests\DiffGenerator;

use PHPUnit\Framework\TestCase;

use function Differ\DiffGenerator\FindDifferences\getDifferences;

class FindDifferencesTest extends TestCase
{
    public function testGetDifference(): void
    {
        $arr1 = [
            "common" => [
                "setting1" => "Value 1",
                "setting2" => 200,
                "setting3" => true,
                "setting6" => [
                    "key" => "value",
                    "doge" => [
                        "wow" => ""
                    ]
                ]
            ]
        ];
        $arr2 = [
        "common" => [
            "follow" => false,
            "setting1" => "Value 1",
            "setting3" => null,
            "setting4" => "blah blah",
            "setting5" => [
                "key5" => "value5"
            ],
            "setting6" => [
                "key" => "value",
                "ops" => "vops",
                "doge" => [
                    "wow" => "so much"
                ]
            ]
          ]
        ];

        $expected = [
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

        $actual = getDifferences($arr1, $arr2);
        $this->assertEquals($expected, $actual);
    }
}
