<?php

namespace Differ\Tests\Formatters;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\JsonFormatter\format;

class JsonFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $expected = <<<STR
        {
            "common": {
                "state": "unchanged",
                "value": {
                    "setting1": {
                        "state": "unchanged",
                        "value": "Value 1"
                    },
                    "setting2": {
                        "state": "removed",
                        "value": "200"
                    },
                    "setting3": {
                        "state": "changed",
                        "oldValue": "1",
                        "newValue": false
                    },
                    "setting6": {
                        "state": "unchanged",
                        "value": {
                            "key": {
                                "state": "unchanged",
                                "value": "value"
                            },
                            "doge": {
                                "state": "unchanged",
                                "value": {
                                    "wow": {
                                        "state": "changed",
                                        "oldValue": false,
                                        "newValue": "so much"
                                    }
                                }
                            },
                            "ops": {
                                "state": "add",
                                "value": "vops"
                            }
                        }
                    },
                    "follow": {
                        "state": "add",
                        "value": false
                    },
                    "setting4": {
                        "state": "add",
                        "value": "blah blah"
                    },
                    "setting5": {
                        "state": "add",
                        "value": {
                            "key5": {
                                "state": "unchanged",
                                "value": "value5"
                            }
                        }
                    }
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

        $actual = format($diffs);
        $this->assertEquals($expected, $actual);
    }
}
