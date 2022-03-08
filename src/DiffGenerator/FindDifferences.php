<?php

namespace Differ\DiffGenerator\FindDifferences;

function getDifferences(array $arr1, array $arr2): array
{
    $allKeys = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));

    $diffBuilder = [];
    foreach ($allKeys as $key) {
        $keyState = getKeyState($arr1, $arr2, $key);
        if ($keyState !== 'unchanged') {
            $value = $keyState === 'add' ? $arr2[$key] : $arr1[$key];
            $diffBuilder[] = generateMeta($keyState, $key, $value);
            continue;
        }

        if ($arr2[$key] === $arr1[$key]) {
            $diffBuilder[] = generateMeta('unchanged', $key, $arr1[$key]);
            continue;
        }

        if (is_array($arr1[$key]) && is_array($arr2[$key])) {
            $newValue = getDifferences($arr1[$key], $arr2[$key]);
            $diffBuilder[] = generateMeta('unchanged', $key, $newValue);
            continue;
        }
        $diffBuilder[] = generateMeta('changed', $key, [$arr1[$key], $arr2[$key]]);
    }

    return formatEntriesToMeta($diffBuilder);
}

function getKeyState(array $keys1, array $keys2, $key): string
{
    if (!in_array($key, array_keys($keys2), true)) {
        return 'removed';
    }
    if (!in_array($key, array_keys($keys1), true)) {
        return 'add';
    }

    return 'unchanged';
}

function generateMeta(string $state, $key, $value): array
{
    if ($state !== 'changed') {
        return ['key' => $key, 'state' => $state, 'value' => $value];
    }

    $oldValue = $value[0];
    $newValue = $value[1];

    return ['key' => $key, 'state' => $state,
        'oldValue' => $oldValue, 'newValue' => $newValue];
}

function formatEntriesToMeta(array $diffs): array
{
    $keys = array_keys($diffs);

    $result = [];
    foreach ($keys as $key) {
        $meta = $diffs[$key];
        if (!is_array($meta)) {
            $result[] = generateMeta('unchanged', $key, $meta);
            continue;
        }

        if (!array_key_exists('state', $meta)) {
            $newValue = formatEntriesToMeta($meta);
            $result[] = generateMeta('unchanged', $key, $newValue);
            continue;
        }

        $result[] = formatMetaValueToMeta($meta);
    }

    return $result;
}

function formatMetaValueToMeta(array $meta): array
{
    $key = $meta['key'];
    if ($meta['state'] === 'changed') {
        $oldValue = is_array($meta['oldValue'])
            ? formatEntriesToMeta($meta['oldValue'])
            : $meta['oldValue'];

        $newValue = is_array($meta['newValue'])
            ? formatEntriesToMeta($meta['newValue'])
            : $meta['newValue'];

        return generateMeta('changed', $key, [$oldValue, $newValue]);
    }

    if (is_array($meta['value'])) {
        $newValue = formatEntriesToMeta($meta['value']);

        return generateMeta($meta['state'], $key, $newValue);
    }

    return generateMeta($meta['state'], $key, $meta['value']);
}
