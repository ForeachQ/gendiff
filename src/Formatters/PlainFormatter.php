<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Utils\Stringify\toString;
use function Differ\Utils\Sort\quickSort;

function format(array $diffs): string
{
    return implode("\n", recursiveFormat($diffs, ''));
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
        if (is_array($value)) {
            $result = implode("\n", recursiveFormat($value, $key));
        } else {
            $result = '';
        }

        if ($state === 'add') {
            return sprintf("Property '%s' was added with value: %s", $key, toString([$value])) . $result;
        }
        if ($state === 'removed') {
            return sprintf("Property '%s' was removed", $key) . $result;
        }
        if ($state === 'changed') {
            if (is_string($meta['oldValue'])) {
                $oldValue = sprintf("'%s'", toString([$meta['oldValue']]));
            } else {
                $oldValue = toString([$meta['oldValue']]);
            }
            if (is_string($meta['newValue'])) {
                $newValue = sprintf("'%s'", toString([$meta['newValue']]));
            } else {
                $newValue = toString([$meta['newValue']]);
            }
            return sprintf("Property '%s' was updated. From %s to %s", $key, $oldValue, $newValue) . $result;
        }

        return $result;
    }, $sortedDiffs);

    return array_filter($output);
}
