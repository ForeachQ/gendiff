<?php

namespace Differ\DiffGenerator\FindDifferences;

function getDifferences(array $arr1, array $arr2): array
{
    $allEntries = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));

    $diffBuilder = [];
    foreach ($allEntries as $key) {
        if (!in_array($key, array_keys($arr2), true)) {
            $diffBuilder[$key] = ['state' => 'removed', 'value' => $arr1[$key]];
            continue;
        }
        if (!in_array($key, array_keys($arr1), true)) {
            $diffBuilder[$key] = ['state' => 'add', 'value' => $arr2[$key]];
            continue;
        }
        if ($arr2[$key] !== $arr1[$key]) {
            if (is_array($arr1[$key]) && is_array($arr2[$key])) {
                $newValue = getDifferences($arr1[$key], $arr2[$key]);
                $diffBuilder[$key] = ['state' => 'unchanged', 'value' => $newValue];
                continue;
            }
            $diffBuilder[$key] = ['state' => 'changed', 'oldValue' => $arr1[$key], 'newValue' => $arr2[$key]];
            continue;
        }
        $diffBuilder[$key] = ['state' => 'unchanged', 'value' => $arr1[$key]];
    }

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
