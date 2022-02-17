<?php

namespace Differ\Utils\Stringify;

function toString($value): string
{
    $result = $value;
    if ($value === true) {
        $result = 'true';
    }
    if ($value === false) {
        $result = 'false';
    }
    if ($value === null) {
        $result = 'null';
    }
    if (is_array($value)) {
        $result = '[complex value]';
    }

    return (string) $result;
}
