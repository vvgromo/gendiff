<?php
function render($data)
{
    $json = json_encode(prepare($data), JSON_PRETTY_PRINT);
    $strings = explode(PHP_EOL, str_replace(['"', ','], "", $json));
    $result = array_map(function ($value) {
        $strWithoutSpaces = str_split(trim($value));
        if ($strWithoutSpaces[0] === '-' || $strWithoutSpaces[0] === '+') {
            return substr($value, 2);
        }
        return $value;
    }, $strings);

    return implode(PHP_EOL, $result);
}

function prepare($data)
{
    $preparation = array_reduce($data, function ($acc, $node) {
        $type = $node['type'];
        $key = $node['key'];
        if ($type == 'notChanged') {
            $acc[$key] = $node['value'];
        } elseif ($type == 'changed') {
            $acc["- {$key}"] = $node['oldValue'];
            $acc["+ {$key}"] = $node['newValue'];
        } elseif ($type == 'added') {
            $acc["+ {$key}"] = $node['value'];
        } elseif ($type == 'deleted') {
            $acc["- {$key}"] = $node['value'];
        } elseif ($type == 'parent') {
            $acc[$key] = prepare($node['children']);
        }

        return $acc;
    }, []);

    return $preparation;
}