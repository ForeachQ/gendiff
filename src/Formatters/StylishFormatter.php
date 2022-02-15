<?php

namespace Differ\Formatters\StylishFormatter;

use function Differ\Utils\Stringify\toString;

function format(array $diffs): string
{
    $diffs = prepareDiffs($diffs);
    return recursiveFormat($diffs) . "\n";
}

function recursiveFormat(array $diffs): string
{
    usort($diffs, fn (array $arr1, array $arr2) => $arr1['key'] <=> $arr2['key']);

    $output = array_map(function (array $entry) {
        $mod = $entry['mod'];
        $key = $entry['key'];
        $value = $entry['val'];
        if (is_array($value)) {
            $value = recursiveFormat($value);
            $value = array_map(
                fn(string $str) => sprintf("%4s%s", ' ', $str),
                array_slice(explode("\n", $value), 1)
            );
            $value = ["{", ...$value];
            $value = implode("\n", $value);
        }

        switch ($mod) {
            case 'add':
                return sprintf("%3s %s: %s", '+', $key, toString($value));
            case 'removed':
                return sprintf("%3s %s: %s", '-', $key, toString($value));
            case 'unchanged':
                return sprintf("%3s %s: %s", '', $key, toString($value));
        }
        return '';
    }, $diffs);
    $output = ['{', ...$output, '}'];

    return implode("\n", $output);
}

function prepareDiffs(array $diffs): array
{
    $resultDiffs = [];
    foreach ($diffs as $diff) {
        if ($diff['mod'] === 'changed') {
            $resultDiffs[] = ['mod' => 'removed', 'key' => $diff['key'], 'val' => $diff['val'][0]];
            $resultDiffs[] = ['mod' => 'add', 'key' => $diff['key'], 'val' => $diff['val'][1]];
            continue;
        }
        if (is_array($diff['val'])) {
            $diff['val'] = prepareDiffs($diff['val']);
        }

        $resultDiffs[] = $diff;
    }

    return $resultDiffs;
}
