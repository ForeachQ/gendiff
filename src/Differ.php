<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\ParserFactory\getParser;
use function Differ\DiffGenerator\FindDifferences\getDifferences;
use function Differ\Formatters\FormatterFactory\getFormatter;

/**
 * @throws Exception
 */
function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $parser1 = getParser($path1);
    $array1 = $parser1($path1);

    $parser2 = getParser($path2);
    $array2 = $parser2($path2);

    $diffs = getDifferences($array1, $array2);
    $formatter = getFormatter($format);

    return $formatter($diffs);
}
