<?php

namespace Gendiff\Generator;

use Symfony\Component\Yaml\Yaml;

use function Gendiff\Parsers\parse;
use function Funct\Collection\union;
use function Gendiff\Formatters\Pretty\renderPretty;
use function Gendiff\Formatters\Plain\renderPlain;
use function Gendiff\Formatters\Plain\renderJson;

function generateDiff($filePath1, $filePath2, $format)
{
    $extension1 = pathinfo($filePath1, PATHINFO_EXTENSION);
    $extension2 = pathinfo($filePath2, PATHINFO_EXTENSION);
    $firstContent = file_get_contents($filePath1);
    $secondContent = file_get_contents($filePath2);
    $firstData = parse($firstContent, $extension1);
    $secondData = parse($secondContent, $extension2);
    $diff = createDiff($firstData, $secondData);

    return render($diff, $format);
}

function createDiff($data1, $data2)
{
    $allKeys = array_values(union(array_keys($data1), array_keys($data2)));
    $diff = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data2)) {
            $result = ['type' => 'deleted', 'key' => $key, 'value' => $data1[$key]];
        } elseif (!array_key_exists($key, $data1)) {
            $result = ['type' => 'added', 'key' => $key, 'value' => $data2[$key]];
        } elseif ($data1[$key] === $data2[$key]) {
            $result = ['type' => 'notChanged', 'key' => $key, 'value' => $data1[$key]];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            $result = [
                'type' => 'parent',
                'key' => $key,
                'children' => createDiff($data1[$key], $data2[$key])
            ];
        } else {
            $result = [
                'type' => 'changed',
                'key' => $key,
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key]
            ];
        }
        return $result;
    }, $allKeys);
    
    return $diff;
}

function render($diff, $format)
{
    $result = [
        'pretty' => function ($diff) {
            return renderPretty($diff);
        },
        'plain' => function ($diff) {
            return renderPlain($diff);
        },
        'json' => function ($diff) {
            return renderJson($diff);
        }
    ];
    return $result[$format]($diff);
}
