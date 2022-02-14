<?php

namespace Differ\Parsers\ParserFactory;

use Exception;
use Symfony\Component\Yaml\Exception\ParseException;

use function Differ\Parsers\YamlParser\parse as parseYaml;
use function Differ\Parsers\JsonParser\parse as parseJson;

function parse(string $filepath): array
{
    $arr = explode('.', $filepath);
    $extension = end($arr);
    switch ($extension) {
        case 'yml':
        case 'yaml':
            try {
                $result = parseYaml($filepath);
            } catch (ParseException $e) {
                throw new Exception(sprintf("Couldn't parse file %s", $filepath));
            }
            break;
        case 'json':
            $result = parseJson($filepath);
            break;
        default:
            throw new Exception("No such file extension support.");
    }

    return $result;
}
