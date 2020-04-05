<?php

namespace Gendiff\Formatters\Pretty;

function renderPretty($data, $depth = 0)
{
    $shift = str_repeat('    ', $depth);
    $preparation = array_map(function ($node) use ($depth, $shift) {
        $type = $node['type'];
        $key = $node['key'];
        switch ($type) {
            case 'notChanged':
                $value = formatValue($node['value'], $depth + 1);
                $result = "{$shift}    {$key}: {$value}";
                break;
            case 'changed':
                $oldValue = formatValue($node['oldValue'], $depth + 1);
                $newValue = formatValue($node['newValue'], $depth + 1);
                $result = "{$shift}  - {$key}: {$oldValue}\n{$shift}  + {$key}: {$newValue}";
                break;
            case 'added':
                $value = formatValue($node['value'], $depth + 1);
                $result = "{$shift}  + {$key}: {$value}";
                break;
            case 'deleted':
                $value = formatValue($node['value'], $depth + 1);
                $result = "{$shift}  - {$key}: {$value}";
                break;
            case 'parent':
                $children = renderPretty($node['children'], $depth + 1);
                $result = "{$shift}    {$key}: {$children}";
                break;
            default:
                throw new \Exception("Unknown type: {$type}");
                break;
        }
        return $result;
    }, $data);
    $result = implode("\n", $preparation);
    return "{\n{$result}\n{$shift}}";
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
    }, explode("\n", $str));

    return implode("\n", $strings);
}
