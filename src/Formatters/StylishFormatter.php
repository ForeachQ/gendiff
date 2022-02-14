<?php

namespace Differ\Formatters\StylishFormatter;

function format(array $diffs): string
{
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
        return sprintf("%3s %s: %s", $mod, $key, toString($value));
    }, $diffs);
    $output = ['{', ...$output, '}'];

    return implode("\n", $output);
}

function toString($value): string
{
    if ($value === true) {
        return 'true';
    }
    if ($value === false) {
        return 'false';
    }
    if ($value === null) {
        return 'null';
    }

    return (string) $value;
}
