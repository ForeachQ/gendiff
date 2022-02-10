<?php

namespace Differ\Parsers\JsonParser;

function parse(string $filepath): array
{
    $file = fopen($filepath, "r") or die("Unable to open " . $filepath);
    return json_decode(fread($file, filesize($filepath)), true);
}
