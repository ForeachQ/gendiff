<?php

namespace Differ\Formatters\StylishFormatter;

use function Differ\Utils\Sort\quickSort;
use function Differ\Utils\Stringify\toString;

function format(array $diffs): string
{
    return recursiveFormat($diffs);
}

function recursiveFormat(array $diffs): string
{
    $sortedDiffs = quickSort($diffs, fn (array $arr1, array $arr2) => $arr1['key'] <=> $arr2['key']);
    $output = [];

    foreach ($sortedDiffs as $meta) {
        $key = $meta['key'];
        $state = $meta['state'];
        $value = $meta['value'] ?? null;

        if ($state !== 'changed') {
            $output[] = generateStylishString($state, $key, $value);
            continue;
        }

        $oldStr = generateStylishString('removed', $key, $meta['oldValue']);
        $newStr = generateStylishString('add', $key, $meta['newValue']);
        $output[] = implode("\n", [$oldStr, $newStr]);
    }

    $normalizedOutput = ['{', ...$output, '}'];

    return implode("\n", $normalizedOutput);
}

function generateStylishString(string $mode, $key, $value): string
{
    $sign = '';
    switch ($mode) {
        case 'add':
            $sign = '+';
            break;
        case 'removed':
            $sign = '-';
            break;
    }

    if (is_array($value)) {
        $value = arrayToString($value);
    }

    return sprintf("%3s %s: %s", $sign, $key, toString($value));
}

function arrayToString(array $array): string
{
    $strings = recursiveFormat($array);
    $tabStrings = array_map(
        fn(string $str) => sprintf("%4s%s", ' ', $str),
        array_slice(explode("\n", $strings), 1)
    );

    $resultStrings = ["{", ...$tabStrings];

    return implode("\n", $resultStrings);
}
