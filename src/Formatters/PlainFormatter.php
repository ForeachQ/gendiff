<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Utils\Stringify\toString;
use function Differ\Utils\Sort\quickSort;

function format(array $diffs): string
{
    return implode("\n", recursiveFormat($diffs, '')) . "\n";
}

function recursiveFormat(array $diffs, string $parentName): array
{
    $sortedDiffs = quickSort($diffs, fn (array $arr1, array $arr2) => $arr1['key'] <=> $arr2['key']);
    $output = array_map(function ($meta) use ($parentName) {
        if ($parentName === '') {
            $key = $meta['key'];
        } else {
            $key = sprintf("%s.%s", $parentName, $meta['key']);
        }
        $state = $meta['state'];

        if (isset($meta['value'])) {
            if (is_string($meta['value'])) {
                $value = sprintf("'%s'", $meta['value']);
            } else {
                $value = $meta['value'];
            }
        } else {
            $value = null;
        }

        $resultStrings = [];
        if ($state === 'add') {
            $resultStrings[] = sprintf("Property '%s' was added with value: %s", $key, toString($value));
        }
        if ($state === 'removed') {
            $resultStrings[] = sprintf("Property '%s' was removed", $key);
        }
        if ($state === 'changed') {
            if (is_string($meta['oldValue'])) {
                $oldValue = sprintf("'%s'", toString($meta['oldValue']));
            } else {
                $oldValue = toString($meta['oldValue']);
            }
            if (is_string($meta['newValue'])) {
                $newValue = sprintf("'%s'", toString($meta['newValue']));
            } else {
                $newValue = toString($meta['newValue']);
            }
            $resultStrings[] = sprintf("Property '%s' was updated. From %s to %s", $key, $oldValue, $newValue);
        }

        if (is_array($value)) {
            $resultStrings = array_merge($resultStrings, recursiveFormat($value, $key));
        }

        return implode("\n", $resultStrings);
    }, $sortedDiffs);

    return array_filter($output);
}
