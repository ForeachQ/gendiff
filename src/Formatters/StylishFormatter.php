<?php

namespace Differ\Formatters\StylishFormatter;

use function Differ\Utils\Stringify\toString;

function format(array $diffs): string
{
    return recursiveFormat($diffs);
}

function recursiveFormat(array $diffs): string
{
    $keys = array_keys($diffs);
    sort($keys, SORT_STRING);

    $output = array_map(function ($key) use ($diffs) {
        $meta = $diffs[$key];
        $state = $meta['state'];
        $value = $meta['value'] ?? null;
        if ($state !== 'changed' && is_array($value)) {
            $value = arrayToString($value);
        }

        switch ($state) {
            case 'add':
                return sprintf("%3s %s: %s", '+', $key, toString($value));
            case 'removed':
                return sprintf("%3s %s: %s", '-', $key, toString($value));
            case 'unchanged':
                return sprintf("%3s %s: %s", '', $key, toString($value));
            case 'changed':
                $output = [];
                $oldValue = $meta['oldValue'];
                $newValue = $meta['newValue'];
                if (is_array($oldValue)) {
                    $oldValue = arrayToString($oldValue);
                }
                if (is_array($newValue)) {
                    $newValue = arrayToString($newValue);
                }
                $output[] = sprintf("%3s %s: %s", '-', $key, toString($oldValue));
                $output[] = sprintf("%3s %s: %s", '+', $key, toString($newValue));
                return implode("\n", $output);
        }
        return '';
    }, $keys);
    $output = ['{', ...$output, '}'];

    return implode("\n", $output);
}

function arrayToString(array $value): string
{
    $value = recursiveFormat($value);
    $value = array_map(
        fn(string $str) => sprintf("%4s%s", ' ', $str),
        array_slice(explode("\n", $value), 1)
    );
    $value = ["{", ...$value];
    return implode("\n", $value);
}
