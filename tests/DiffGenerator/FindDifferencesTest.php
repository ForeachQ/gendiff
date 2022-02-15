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

        $actual = getDifferences($arr1, $arr2);
        $this->assertEquals($expected, $actual);
    }
}
