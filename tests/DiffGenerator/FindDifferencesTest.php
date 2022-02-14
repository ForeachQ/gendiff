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

        $actual = getDifferences($arr1, $arr2);
        $this->assertEquals($expected, $actual);
    }
}
