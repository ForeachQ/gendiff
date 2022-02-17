<?php

namespace Differ\Differ;

use function Differ\Parsers\ParserFactory\parse;
use function Differ\DiffGenerator\FindDifferences\getDifferences;
use function Differ\Formatters\FormatterFactory\format;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $array1 = parse($path1);
    $array2 = parse($path2);
    $diffs = getDifferences($array1, $array2);
    return format($diffs, $format);
}
