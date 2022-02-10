<?php

namespace Differ\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath): array
{
    return Yaml::parseFile($filepath, Yaml::DUMP_OBJECT_AS_MAP) ?? [];
}
