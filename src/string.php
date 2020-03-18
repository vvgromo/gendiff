<?php

$preparation = array_reduce($data, function ($acc, $node) {
    $type = $node['type'];
    $key = $node['key'];
    if (array_key_exists('value', $node)) {
        $value = json_encode($node['value'], JSON_PRETTY_PRINT);
    }
    if ($type == 'notChanged') {
        $acc[] = "    {$key}: {$value}";
        return $acc;
    }
    if ($type == 'changed') {
        $oldValue = json_encode($node['oldValue'], JSON_PRETTY_PRINT);
        $newValue = json_encode($node['newValue'], JSON_PRETTY_PRINT);
        $acc[] = "  - {$key}: {$oldValue}";
        $acc[] = "  + {$key}: {$newValue}";
        return $acc;
    }
    if ($type == 'added') {
        $acc[] = "  + {$key}: {$value}";
        return $acc;
    }
    if ($type == 'deleted') {
        $acc[] = "  - {$key}: {$value}";
        return $acc;
    }
    if ($type == 'parent') {
        $children = render($node['children']);
        $acc[] = "    {$key}: {$children}";
        return $acc;
    }
}, ['{']);
array_push($preparation, '}');

return implode(PHP_EOL, $preparation);