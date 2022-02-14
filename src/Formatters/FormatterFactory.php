<?php

namespace Differ\Formatters\FormatterFactory;

use Exception;

use function Differ\Formatters\StylishFormatter\format as formatStylish;

function format(array $data, string $format = 'stylish'): string
{
    switch ($format) {
        case 'stylish':
            $result = formatStylish($data);
            break;
        default:
            throw new Exception("No such format support.");
    }
    return $result;
}
