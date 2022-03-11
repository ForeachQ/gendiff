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
    $output = [];

    foreach ($sortedDiffs as $meta) {
        $key = sprintf("%s.%s", $parentName, $meta['key']);
        if ($parentName === '') {
            $key = $meta['key'];
        }

        $state = $meta['state'];
        $value = $meta['value'] ?? null;
        if ($state === 'changed') {
            $output[] = generatePlainString($state, $key, [$meta['oldValue'], $meta['newValue']]);
            continue;
        }

        $reportStr = generatePlainString($state, $key, $value);
        if (strlen($reportStr) !== 0) {
            $output[] = $reportStr;
        }

        if (is_array($value)) {
            $output = [...$output, ...recursiveFormat($value, $key)];
        }
    }

    return $output;
}

function generatePlainString(string $mode, $key, $value): string
{
    switch ($mode) {
        case 'added':
            $value = is_string($value)
                ? sprintf("'%s'", $value)
                : toString($value);

            return sprintf("Property '%s' was added with value: %s", $key, $value);
        case 'changed':
            $oldValue = is_string($value[0])
                ? sprintf("'%s'", $value[0])
                : toString($value[0]);

            $newValue = is_string($value[1])
                ? sprintf("'%s'", $value[1])
                : toString($value[1]);

            return sprintf("Property '%s' was updated. From %s to %s", $key, $oldValue, $newValue);
        case 'removed':
            return sprintf("Property '%s' was removed", $key);
    }

    return '';
}
