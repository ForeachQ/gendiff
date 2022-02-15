<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Utils\Stringify\toString;

function format(array $diffs): string
{
    return implode("\n", recursiveFormat($diffs, '')) . "\n";
}

function recursiveFormat(array $diffs, string $parentName): array
{
    usort($diffs, fn (array $arr1, array $arr2) => $arr1['key'] <=> $arr2['key']);
    $strings = array_map(function (array $diff) use ($parentName) {
        ['mod' => $mod, 'key' => $key, 'val' => $val] = $diff;
        if ($parentName !== '') {
            $key = sprintf("%s.%s", $parentName, $key);
        }
        $result = [];

        if (is_string($val)) {
            $val = sprintf("'%s'", $val);
        }

        if ($mod === 'add') {
            $result[] = sprintf("Property '%s' was added with value: %s", $key, toString($val));
        }
        if ($mod === 'removed') {
            $result[] = sprintf("Property '%s' was removed", $key);
        }
        if ($mod === 'changed') {
            if (is_string($val[0])) {
                $val[0] = sprintf("'%s'", $val[0]);
            }
            if (is_string($val[1])) {
                $val[1] = sprintf("'%s'", $val[1]);
            }
            $result[] = sprintf(
                "Property '%s' was updated. From %s to %s",
                $key,
                toString($val[0]),
                toString($val[1])
            );
        }

        if (is_array($val) && $mod !== 'changed') {
            $result = array_merge($result, recursiveFormat($val, $key));
        }

        return implode("\n", $result);
    }, $diffs);

    return array_filter($strings);
}
