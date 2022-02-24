<?php

namespace Differ\Formatters\FormatterFactory;

use Exception;

use function Differ\Formatters\StylishFormatter\format as formatStylish;
use function Differ\Formatters\PlainFormatter\format as formatPlain;
use function Differ\Formatters\JsonFormatter\format as formatJson;

function getFormatter(string $format = 'stylish'): callable
{
    switch ($format) {
        case 'stylish':
            return fn(array $data) => formatStylish($data);
        case 'plain':
            return fn(array $data) => formatPlain($data);
        case 'json':
            return fn(array $data) => formatJson($data);
        default:
            throw new Exception("No such format support.");
    }
}
