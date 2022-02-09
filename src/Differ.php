<?php

namespace Differ\Differ;

function genDiff(string $path1, string $path2): string
{
    $file1 = fopen($path1, "r") or die("Unable to open " . $path1);
    $file2 = fopen($path2, "r") or die("Unable to open " . $path2);
    $json1 =  json_decode(fread($file1, filesize($path1)), true);
    $json2 =  json_decode(fread($file2, filesize($path2)), true);
    $allEntries = array_merge($json1, $json2);

    $diffStringBuilder = [];
    foreach ($allEntries as $key => $value) {
        if (!in_array($key, array_keys($json2), true)) {
            $diffStringBuilder[] = ['-', sprintf("%s: %s", $key, normalizeBool($value))];
            continue;
        }
        if (!in_array($key, array_keys($json1), true)) {
            $diffStringBuilder[] = ['+', sprintf("%s: %s", $key, normalizeBool($value))];
            continue;
        }
        if ($json2[$key] !== $json1[$key]) {
            $diffStringBuilder[] = ['-', sprintf("%s: %s", $key, normalizeBool($json1[$key]))];
            $diffStringBuilder[] = ['+', sprintf("%s: %s", $key, normalizeBool($json2[$key]))];
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
