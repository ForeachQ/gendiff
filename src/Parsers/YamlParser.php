<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;
use Exception;

/**
 * @throws Exception
 */
function parse(string $filepath): array
{
    $yaml = Yaml::parseFile($filepath, Yaml::DUMP_OBJECT_AS_MAP) ?? [];
    if (!is_array($yaml)) {
        throw new Exception(sprintf("Couldn't parse file %s.", $filepath));
    }

    return $yaml;
}
