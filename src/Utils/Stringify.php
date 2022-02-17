<?php

namespace Differ\Utils\Stringify;

function toString(array $value): string
{
    if ($value[0] === true) {
        return 'true';
    }
    if ($value[0] === false) {
        return 'false';
    }
    if ($value[0] === null) {
        return 'null';
    }
    if (is_array($value[0])) {
        return '[complex value]';
    }

    return (string) $value[0];
}
