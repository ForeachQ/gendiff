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
                "setting2" => true,
                "setting3" => true,
                "setting6" => [
                    "key" => "value",
                    "doge" => [
                        "wow" => [
                            "key" => true
                        ]
                    ]
                ]
            ]
        ];
        $arr2 = [
            "common" => [
                "follow" => false,
                "setting1" => "Value 1",
                "setting2" => 300,
                "setting3" => null,
                "setting4" => "blah blah",
                "setting5" => [
                    "key5" => "value5"
                ],
                "setting6" => [
                    "key" => "value",
                    "ops" => "vops",
                    "doge" => [
                        "wow" => [
                            "yes" => null
                        ]
                    ]
                ]
            ]
        ];

        $expected = [
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

        $actual = getDifferences($arr1, $arr2);

        $this->assertEquals($expected, $actual);
    }
}
