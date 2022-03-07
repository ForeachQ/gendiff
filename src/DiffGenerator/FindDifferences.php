<?php

namespace Differ\DiffGenerator\FindDifferences;

function getDifferences(array $arr1, array $arr2): array
{
    $allEntries = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));

    $diffBuilder = array_map(function ($key) use ($arr2, $arr1) {
        if (!in_array($key, array_keys($arr2), true)) {
            return ['key' => $key, 'state' => 'removed', 'value' => $arr1[$key]];
        }
        if (!in_array($key, array_keys($arr1), true)) {
            return ['key' => $key, 'state' => 'add', 'value' => $arr2[$key]];
        }
        if ($arr2[$key] !== $arr1[$key]) {
            if (is_array($arr1[$key]) && is_array($arr2[$key])) {
                $newValue = getDifferences($arr1[$key], $arr2[$key]);
                return ['key' => $key, 'state' => 'unchanged', 'value' => $newValue];
            }
            return ['key' => $key, 'state' => 'changed', 'oldValue' => $arr1[$key], 'newValue' => $arr2[$key]];
        }
        return ['key' => $key, 'state' => 'unchanged', 'value' => $arr1[$key]];
    }, $allEntries);

    return normalizeDifferences($diffBuilder);
}

function normalizeDifferences(array $diffs): array
{
    $keys = array_keys($diffs);
    return array_map(function ($key) use ($diffs) {
        $meta = $diffs[$key];
        if (!is_array($meta)) {
            return ['key' => $key, 'state' => 'unchanged', 'value' => $meta];
        }
        if (!array_key_exists('state', $meta)) {
            $newValue = normalizeDifferences($meta);
            return ['key' => $key, 'state' => 'unchanged', 'value' => $newValue];
        }
        $metaKey = $meta['key'];
        if ($meta['state'] !== 'changed' && is_array($meta['value'])) {
            $newValue = normalizeDifferences($meta['value']);
            return ['key' => $metaKey, 'state' => $meta['state'], 'value' => $newValue];
        }
        if ($meta['state'] === 'changed') {
            $oldValue = is_array($meta['oldValue'])
                ? normalizeDifferences($meta['oldValue'])
                : $meta['oldValue'];

            $newValue = is_array($meta['newValue'])
                ? normalizeDifferences($meta['newValue'])
                : $meta['newValue'];

            return ['key' => $metaKey, 'state' => 'changed',
                'oldValue' => $oldValue, 'newValue' => $newValue];
        }

        return ['key' => $metaKey, 'state' => $meta['state'], 'value' => $meta['value']];
    }, $keys);
}
