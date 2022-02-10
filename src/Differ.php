<?php

namespace Differ\Differ;

function genDiff(array $array1, array $array2): string
{
    $allEntries = array_merge($array1, $array2);

    $diffStringBuilder = [];
    foreach ($allEntries as $key => $value) {
        if (!in_array($key, array_keys($array2), true)) {
            $diffStringBuilder[] = ['-', sprintf("%s: %s", $key, normalizeBool($value))];
            continue;
        }
        if (!in_array($key, array_keys($array1), true)) {
            $diffStringBuilder[] = ['+', sprintf("%s: %s", $key, normalizeBool($value))];
            continue;
        }
        if ($array2[$key] !== $array1[$key]) {
            $diffStringBuilder[] = ['-', sprintf("%s: %s", $key, normalizeBool($array1[$key]))];
            $diffStringBuilder[] = ['+', sprintf("%s: %s", $key, normalizeBool($array2[$key]))];
            continue;
        }
        $diffStringBuilder[] = [' ', sprintf("%s: %s", $key, normalizeBool($value))];
    }

    usort($diffStringBuilder, function (array $diff1, array $diff2): int {
        $key1 = explode(':', $diff1[1])[0];
        $key2 = explode(':', $diff2[1])[0];
        return $key1 <=> $key2;
    });
    $diffStrings = array_map(function (array $diff): string {
        return "  " . implode(' ', $diff);
    }, $diffStringBuilder);
    $diffStrings = ['{', ...$diffStrings, "}\n"];

    return implode("\n", $diffStrings);
}

function normalizeBool($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}
