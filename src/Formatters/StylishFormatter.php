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
    $output = array_map(function ($meta) {
        $key = $meta['key'];
        $state = $meta['state'];
        if ($state !== 'changed' && is_array($meta['value'])) {
            $value = arrayToString($meta['value']);
        } else {
            $value = $meta['value'] ?? null;
        }

        switch ($state) {
            case 'add':
                $resultStr = sprintf("%3s %s: %s", '+', $key, toString($value));
                break;
            case 'removed':
                $resultStr = sprintf("%3s %s: %s", '-', $key, toString($value));
                break;
            case 'unchanged':
                $resultStr = sprintf("%3s %s: %s", '', $key, toString($value));
                break;
            case 'changed':
                if (is_array($meta['oldValue'])) {
                    $oldValue = arrayToString($meta['oldValue']);
                } else {
                    $oldValue = $meta['oldValue'];
                }
                if (is_array($meta['newValue'])) {
                    $newValue = arrayToString($meta['newValue']);
                } else {
                    $newValue = $meta['newValue'];
                }
                $oldStr = sprintf("%3s %s: %s", '-', $key, toString($oldValue));
                $newStr = sprintf("%3s %s: %s", '+', $key, toString($newValue));
                $resultStr = implode("\n", [$oldStr, $newStr]);
                break;
            default:
                $resultStr = '';
        }
        return $resultStr;
    }, $sortedDiffs);
    $normalizedOutput = ['{', ...$output, '}'];

    return implode("\n", $normalizedOutput);
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
