<?php

namespace Gendiff\Renderings;

function render($data)
{
    $result = array_reduce($data, function ($acc, $node) {
        $type = $node['type'];
        $key = $node['key'];
        if ($type == 'notChanged') {
            $acc[$key] = $node['value'];
            return $acc;
        }
        if ($type == 'changed') {
            $acc["- {$key}"] = $node['oldValue'];
            $acc["+ {$key}"] = $node['newValue'];
            return $acc;
        }
    }, []);
}