<?php

namespace Differ\Parsers\JsonParser;

use Exception;

/**
 * @throws Exception
 */
function parse(string $filepath): array
{
    if (!file_exists($filepath)) {
        throw new Exception(sprintf("File %s is not exist.", $filepath));
    }

    $jsonStr = file_get_contents($filepath);
    if ($jsonStr === false) {
        throw new Exception(sprintf("File %s open failed.", $filepath));
    }

    $json = json_decode($jsonStr, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception(sprintf("Couldn't parse file %s. %s", $filepath, json_last_error_msg()));
    }

    return $json;
}
