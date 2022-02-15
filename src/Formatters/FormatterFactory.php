<?php

namespace Differ\Formatters\FormatterFactory;

use Exception;

use function Differ\Formatters\StylishFormatter\format as formatStylish;
use function Differ\Formatters\PlainFormatter\format as formatPlain;

function format(array $data, string $format = 'stylish'): string
{
    switch ($format) {
        case 'stylish':
            $result = formatStylish($data);
            break;
        case 'plain':
            $result = formatPlain($data);
            break;
        default:
            throw new Exception("No such format support.");
    }
    return $result;
}
