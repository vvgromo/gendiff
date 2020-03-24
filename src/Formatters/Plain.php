<?php

namespace Gendiff\Formatters\Plain;

function renderPlain($data, $prefix = '')
{
    $preparation = array_reduce($data, function ($acc, $node) use ($prefix) {
        $type = $node['type'];
        $key = $node['key'];
        if (array_key_exists('value', $node)) {
            $value = formatValue($node['value']);
        }
        if ($type == 'changed') {
            $oldValue = formatValue($node['oldValue']);
            $newValue = formatValue($node['newValue']);
            $acc[] = "Property '{$prefix}{$key}' was changed. From '{$oldValue}' to '{$newValue}'";
        } elseif ($type == 'added') {
            $acc[] = "Property '{$prefix}{$key}' was added with value: '{$value}'";
        } elseif ($type == 'deleted') {
            $acc[] = "Property '{$prefix}{$key}' was removed";
        } elseif ($type == 'parent') {
            $acc[] = renderPlain($node['children'], "{$key}.");
        }
        return $acc;
    }, []);
    return implode(PHP_EOL, $preparation);
}

function formatValue($value)
{
    if (is_array($value)) {
        return 'complex value';
    }
    return str_replace(['"', ','], "", json_encode($value, JSON_PRETTY_PRINT));
}
