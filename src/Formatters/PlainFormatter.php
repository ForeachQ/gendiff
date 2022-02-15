<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Utils\Stringify\toString;

function format(array $diffs): string
{
    return implode("\n", recursiveFormat($diffs, ''));
}

function recursiveFormat(array $diffs, string $parentName): array
{
    $keys = array_keys($diffs);
    sort($keys, SORT_STRING);

    $strings = array_map(function ($key) use ($diffs, $parentName) {
        $meta = $diffs[$key];
        $state = $meta['state'];
        $value = $meta['value'] ?? null;
        if ($parentName !== '') {
            $key = sprintf("%s.%s", $parentName, $key);
        }
        $result = [];

        if (is_string($value)) {
            $value = sprintf("'%s'", $value);
        }

        if ($state === 'add') {
            $result[] = sprintf("Property '%s' was added with value: %s", $key, toString($value));
        }
        if ($state === 'removed') {
            $result[] = sprintf("Property '%s' was removed", $key);
        }
        if ($state === 'changed') {
            if (is_string($meta['oldValue'])) {
                $meta['oldValue'] = sprintf("'%s'", toString($meta['oldValue']));
            }
            if (is_string($meta['newValue'])) {
                $meta['newValue'] = sprintf("'%s'", toString($meta['newValue']));
            }
            $result[] = sprintf(
                "Property '%s' was updated. From %s to %s",
                $key,
                toString($meta['oldValue']),
                toString($meta['newValue'])
            );
        }

        if (is_array($value) && $state !== 'changed') {
            $result = array_merge($result, recursiveFormat($value, $key));
        }

        return implode("\n", $result);
    }, $keys);

    return array_filter($strings);
}
