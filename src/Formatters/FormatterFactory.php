<?php

namespace Differ\Formatters\FormatterFactory;

use Exception;

use function Differ\Formatters\StylishFormatter\format as formatStylish;
use function Differ\Formatters\PlainFormatter\format as formatPlain;
use function Differ\Formatters\JsonFormatter\format as formatJson;

function format(array $data, string $format = 'stylish'): string
{
    switch ($format) {
        case 'stylish':
            $result = formatStylish($data);
            break;
        case 'plain':
            $result = formatPlain($data);
            break;
        case 'json':
            $result = formatJson($data);
            break;
        default:
            throw new Exception("No such format support.");
    }
    return $result;
}
