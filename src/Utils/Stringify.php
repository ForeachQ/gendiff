<?php

namespace Differ\Utils\Stringify;

function toString($value): string
{
    if ($value === true) {
        return 'true';
    }
    if ($value === false) {
        return 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if (is_array($value)) {
        return '[complex value]';
    }

    return (string) $value;
}
