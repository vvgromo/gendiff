<?php

namespace Gendiff\Formatters\Pretty;

function renderJson($data, $depth = 0)
{
    $shift = str_repeat('    ', $depth);
    $preparation = array_reduce($data, function ($acc, $node) use ($depth, $shift) {
        $type = $node['type'];
        $key = $node['key'];
        if (array_key_exists('value', $node)) {
            $value = formatValue($node['value'], $depth + 1);
        }
        if ($type == 'notChanged') {
            $acc[] = "{$shift}    {$key}: {$value}";
        } elseif ($type == 'changed') {
            $oldValue = formatValue($node['oldValue'], $depth + 1);
            $newValue = formatValue($node['newValue'], $depth + 1);
            $acc[] = "{$shift}  - {$key}: {$oldValue}";
            $acc[] = "{$shift}  + {$key}: {$newValue}";
        } elseif ($type == 'added') {
            $acc[] = "{$shift}  + {$key}: {$value}";
        } elseif ($type == 'deleted') {
            $acc[] = "{$shift}  - {$key}: {$value}";
        } elseif ($type == 'parent') {
            $children = renderJson($node['children'], $depth + 1);
            $acc[] = "{$shift}    {$key}: {$children}";
        }
        return $acc;
    }, ['{']);
    array_push($preparation, "{$shift}}");
    return implode(PHP_EOL, $preparation);
}

function formatValue($value, $depth)
{
    $str = str_replace(['"', ','], "", json_encode($value, JSON_PRETTY_PRINT));
    if (!is_array($value)) {
        return $str;
    }
    $shift = str_repeat('    ', $depth);
    $strings = array_map(function ($str) use ($shift) {
        if ($str == '{') {
            return $str;
        }
        return "{$shift}{$str}";
    }, explode(PHP_EOL, $str));

    return implode(PHP_EOL, $strings);
}
