<?php

namespace Gendiff\Generator;

use Symfony\Component\Yaml\Yaml;

use function Gendiff\Parsers\parse;
use function Funct\Collection\union;
use function Gendiff\Formatters\Pretty\renderJson;

function generateDiff($filePath1, $filePath2)
{
    if (!file_exists($filePath1)) {
        $filePath1 = __DIR__ . '/' . $filePath1;
        $filePath2 = __DIR__ . '/' . $filePath2;
    }
    $format1 = pathinfo($filePath1, PATHINFO_EXTENSION);
    $format2 = pathinfo($filePath2, PATHINFO_EXTENSION);
    $firstFileStr = file_get_contents($filePath1);
    $secondFileStr = file_get_contents($filePath2);
    $firstFileData = parse($firstFileStr, $format1);
    $secondFileData = parse($secondFileStr, $format2);
    $diff = createDiff($firstFileData, $secondFileData);

    return renderJson($diff);
}

function createDiff($firstFileData, $secondFileData)
{
    $allKeys = union(array_keys($firstFileData), array_keys($secondFileData));
    $diff = array_reduce($allKeys, function ($d, $key) use ($firstFileData, $secondFileData) {
        if (!array_key_exists($key, $secondFileData)) {
            $d[] = ['type' => 'deleted', 'key' => $key, 'value' => $firstFileData[$key]];
        } elseif (!array_key_exists($key, $firstFileData)) {
            $d[] = ['type' => 'added', 'key' => $key, 'value' => $secondFileData[$key]];
        } elseif ($firstFileData[$key] === $secondFileData[$key]) {
            $d[] = ['type' => 'notChanged', 'key' => $key, 'value' => $firstFileData[$key]];
        } elseif (is_array($firstFileData[$key]) && is_array($secondFileData[$key])) {
            $d[] = ['type' => 'parent',
                    'key' => $key,
                    'children' => createDiff($firstFileData[$key], $secondFileData[$key])
            ];
        } else {
            $d[] = ['type' => 'changed',
                'key' => $key,
                'oldValue' => $firstFileData[$key],
                'newValue' => $secondFileData[$key]
            ];
        }
        return $d;
    }, []);
    
    return $diff;
}
