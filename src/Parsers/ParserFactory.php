<?php

namespace Differ\Parsers\ParserFactory;

use Exception;
use Symfony\Component\Yaml\Exception\ParseException;

use function Differ\Parsers\YamlParser\parse as parseYaml;
use function Differ\Parsers\JsonParser\parse as parseJson;

/**
 * @throws Exception
 */
function getParser(string $filepath): callable
{
    $arr = explode('.', $filepath);
    $extension = end($arr);
    switch ($extension) {
        case 'yml':
        case 'yaml':
            try {
                return fn(string $path) => parseYaml($path);
            } catch (ParseException $e) {
                throw new Exception(sprintf("Couldn't parse file %s", $filepath));
            }
        case 'json':
            return fn(string $path) => parseJson($path);
    }

    throw new Exception("No such file extension support.");
}
