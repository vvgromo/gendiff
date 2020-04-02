<?php

namespace Gendiff\Formatters\Plain;

function renderPlain($data, $prefix = '')
{
    $preparation = array_reduce($data, function ($acc, $node) use ($prefix) {
        $type = $node['type'];
        $key = $node['key'];
        switch ($type) {
            case 'changed':
                $oldValue = formatValue($node['oldValue']);
                $newValue = formatValue($node['newValue']);
                $acc[] = "Property '{$prefix}{$key}' was changed. From '{$oldValue}' to '{$newValue}'";
                break;
            case 'added':
                $value = formatValue($node['value']);
                $acc[] = "Property '{$prefix}{$key}' was added with value: '{$value}'";
                break;
            case 'deleted':
                $acc[] = "Property '{$prefix}{$key}' was removed";
                break;
            case 'parent':
                $acc[] = renderPlain($node['children'], "{$key}.");
                break;
            case 'notChanged':
                break;
            default:
                throw new \Exception("Unknown type: {$type}");
                break;
        }
        return $acc;
    }, []);
    return implode("\n", $preparation);
}

function formatValue($value)
{
    if (is_array($value)) {
        return 'complex value';
    }
    return str_replace(['"', ','], "", json_encode($value, JSON_PRETTY_PRINT));
}
