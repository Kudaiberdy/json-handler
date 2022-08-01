<?php

namespace App\FindInJson;

function isAssocArr(array $array): bool
{
    return count(array_filter(array_keys($array), 'is_string')) > 0;
}

function reduce(array $array, callable $callback, $initial = null)
{
    $result = $initial;

    foreach ($array as $key => $value) {
        $result = $callback($result, $value, $key);
    }

    return $result;
}

function find(string $needle, array|string $nodes, string $ancestry = '', array $paths = [], $nodeName = ''): array|string
{
    if (str_contains($nodeName, $needle)) {
        $paths[] = $ancestry;
        return $paths;
    }

    $newAncestry = ($ancestry === '/') ? "{$ancestry}{$nodeName}" : "$ancestry/$nodeName";

    if (!is_array($nodes)) {
        if (str_contains($nodes, $needle)) {
            $paths[] = $newAncestry;
        }
        return $paths;
    }

    if (!isAssocArr($nodes)) {
        foreach ($nodes as $item) {
            if (str_contains($item, $needle)) {
                $paths[] = $newAncestry;
            }
        }
        return $paths;
    }

    return reduce($nodes, function ($newPaths, $node, $key) use ($needle, $newAncestry) {
        return find($needle, $node, $newAncestry, $newPaths, $key);
    }, $paths);
}

function findInJson(string $needle, $jsonFile)
{
    return [$needle => find($needle, $jsonFile)];
}