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
    if (!file_exists($filePath1)) {
        $filePath1 = __DIR__ . '/' . $filePath1;
        $filePath2 = __DIR__ . '/' . $filePath2;
    }
    $extension1 = pathinfo($filePath1, PATHINFO_EXTENSION);
    $extension2 = pathinfo($filePath2, PATHINFO_EXTENSION);
    $firstFileStr = file_get_contents($filePath1);
    $secondFileStr = file_get_contents($filePath2);
    $firstFileData = parse($firstFileStr, $extension1);
    $secondFileData = parse($secondFileStr, $extension2);
    $diff = createDiff($firstFileData, $secondFileData);

    return render($diff, $format);
}

function createDiff($data1, $data2)
{
    $allKeys = union(array_keys($data1), array_keys($data2));
    $diff = array_reduce($allKeys, function ($d, $key) use ($data1, $data2) {
        if (!array_key_exists($key, $data2)) {
            $d[] = ['type' => 'deleted', 'key' => $key, 'value' => $data1[$key]];
        } elseif (!array_key_exists($key, $data1)) {
            $d[] = ['type' => 'added', 'key' => $key, 'value' => $data2[$key]];
        } elseif ($data1[$key] === $data2[$key]) {
            $d[] = ['type' => 'notChanged', 'key' => $key, 'value' => $data1[$key]];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            $d[] = ['type' => 'parent',
                    'key' => $key,
                    'children' => createDiff($data1[$key], $data2[$key])
            ];
        } else {
            $d[] = ['type' => 'changed',
                'key' => $key,
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key]
            ];
        }
        return $d;
    }, []);
    
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
