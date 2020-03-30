<?php

namespace Gendiff\Formatters\Pretty;

function renderPretty($data, $depth = 0)
{
    $shift = str_repeat('    ', $depth);
    $preparation = array_reduce($data, function ($acc, $node) use ($depth, $shift) {
        $type = $node['type'];
        $key = $node['key'];
        if (array_key_exists('value', $node)) {
            $value = formatValue($node['value'], $depth + 1);
        }
        switch ($type) {
            case 'notChanged':
                $acc[] = "{$shift}    {$key}: {$value}";
                break;
            case 'changed':
                $oldValue = formatValue($node['oldValue'], $depth + 1);
                $newValue = formatValue($node['newValue'], $depth + 1);
                $acc[] = "{$shift}  - {$key}: {$oldValue}";
                $acc[] = "{$shift}  + {$key}: {$newValue}";
                break;
            case 'added':
                $acc[] = "{$shift}  + {$key}: {$value}";
                break;
            case 'deleted':
                $acc[] = "{$shift}  - {$key}: {$value}";
                break;
            case 'parent':
                $children = renderPretty($node['children'], $depth + 1);
                $acc[] = "{$shift}    {$key}: {$children}";
                break;
            default:
                throw new \Exception("Unknown type: {$type}");
                break;
        }
        return $acc;
    }, ['{']);
    $preparation[] = "{$shift}}";
    return implode("\n", $preparation);
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
