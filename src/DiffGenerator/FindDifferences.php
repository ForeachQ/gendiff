<?php

namespace Differ\DiffGenerator\FindDifferences;

function getDifferences(array $arr1, array $arr2): array
{
    $allEntries = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));

    $diffBuilder = array_reduce($allEntries, function ($diffs, $key) use ($arr2, $arr1) {
        if (!in_array($key, array_keys($arr2), true)) {
            $diffs[$key] = ['state' => 'removed', 'value' => $arr1[$key]];
            return $diffs;
        }
        if (!in_array($key, array_keys($arr1), true)) {
            $diffs[$key] = ['state' => 'add', 'value' => $arr2[$key]];
            return $diffs;
        }
        if ($arr2[$key] !== $arr1[$key]) {
            if (is_array($arr1[$key]) && is_array($arr2[$key])) {
                $newValue = getDifferences($arr1[$key], $arr2[$key]);
                $diffs[$key] = ['state' => 'unchanged', 'value' => $newValue];
                return $diffs;
            }
            $diffs[$key] = ['state' => 'changed', 'oldValue' => $arr1[$key], 'newValue' => $arr2[$key]];
            return $diffs;
        }
        $diffs[$key] = ['state' => 'unchanged', 'value' => $arr1[$key]];
        return $diffs;
    }, []);

    return normalizeDifferences($diffBuilder);
}

function normalizeDifferences(array $diffs): array
{
    $keys = array_keys($diffs);
    return array_reduce($keys, function ($acc, $key) use ($diffs) {
        $meta = $diffs[$key];
        if (!is_array($meta)) {
            $acc[$key] = ['state' => 'unchanged', 'value' => $meta];
            return $acc;
        }
        if (!array_key_exists('state', $meta)) {
            $newValue = normalizeDifferences($meta);
            $acc[$key] = ['state' => 'unchanged', 'value' => $newValue];
            return $acc;
        }
        if ($meta['state'] !== 'changed' && is_array($meta['value'])) {
            $newValue = normalizeDifferences($meta['value']);
            $acc[$key] = ['state' => $meta['state'], 'value' => $newValue];
            return $acc;
        }
        if ($meta['state'] === 'changed') {
            if (is_array($meta['oldValue'])) {
                $meta['oldValue'] = normalizeDifferences($meta['oldValue']);
            }
            if (is_array($meta['newValue'])) {
                $meta['newValue'] = normalizeDifferences($meta['newValue']);
            }
            $acc[$key] = ['state' => 'changed', 'oldValue' => $meta['oldValue'], 'newValue' => $meta['newValue']];
            return $acc;
        }

        $acc[$key] = ['state' => $meta['state'], 'value' => $meta['value']];
        return $acc;
    }, []);
}
