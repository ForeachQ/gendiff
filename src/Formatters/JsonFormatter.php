<?php

namespace Differ\Formatters\JsonFormatter;

function format(array $diffs): string
{
    return json_encode($diffs, JSON_PRETTY_PRINT);
}
