<?php

namespace Differ\Utils\Sort;

function quickSort(array $array, callable $func): array
{
    if (count($array) < 2) {
        return $array;
    }

    $pivot_key = key($array);
    $pivot = $array[0];
    $shiftedArray = array_slice($array, 1);

    $loe = array_filter($shiftedArray, function ($val) use ($func, $pivot) {
        return $func($val, $pivot) !== 1;
    });

    $gt = array_filter($shiftedArray, function ($val) use ($func, $pivot) {
        return $func($val, $pivot) === 1;
    });

    return array_merge(
        quickSort($loe, $func),
        [$pivot_key => $pivot],
        quickSort($gt, $func)
    );
}
