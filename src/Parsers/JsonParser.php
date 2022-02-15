<?php

namespace Differ\Parsers\JsonParser;

use Exception;

function parse(string $filepath): array
{
    if (!file_exists($filepath)) {
        throw new Exception(sprintf("File %s was not found.", $filepath));
    }
    $jsonStr = file_get_contents($filepath);
    if ($jsonStr === false) {
        throw new Exception(sprintf("File %s open failed.", $filepath));
    }
    return json_decode($jsonStr, true);
}
