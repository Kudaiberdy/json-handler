<?php

namespace App\Utilities\ArrayHelpers;

function isAssocArr(array $array): bool
{
    return count(array_filter(array_keys($array), 'is_string')) > 0;
}

function arrayReduce(array $array, callable $callback, $initial = null)
{
    $result = $initial;

    foreach ($array as $key => $value) {
        $result = $callback($result, $value, $key);
    }

    return $result;
}

function findInArr(
    string $needle,
    array|string $nodes,
    string $ancestry = '',
    array $paths = [],
    string $nodeName = ''
): array|string {
    if (stripos($nodeName, $needle) !== false) {
        $paths[] = $ancestry;
        return $paths;
    }

    $newAncestry = ($ancestry === '/') ? "{$ancestry}{$nodeName}" : "$ancestry/$nodeName";

    if (!is_array($nodes)) {
        if (stripos($nodes, $needle) !== false) {
            $paths[] = $newAncestry;
        }
        return $paths;
    }

    if (!isAssocArr($nodes)) {
        foreach ($nodes as $item) {
            if (stripos($item, $needle) !== false) {
                $paths[] = $newAncestry;
            }
        }
        return $paths;
    }

    return arrayReduce($nodes, function ($newPaths, $node, $key) use ($needle, $newAncestry) {
        return findInArr($needle, $node, $newAncestry, $newPaths, $key);
    }, $paths);
}
