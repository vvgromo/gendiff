<?php

namespace Gendiff\Generator;

use function Gendiff\Parsers\parse;

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
    $result = json_encode($diff, JSON_PRETTY_PRINT);

    return str_replace(['"', ','], "", $result);
}

function createDiff($firstFileData, $secondFileData)
{
    $diff = array_reduce(array_keys($firstFileData), function ($d, $key) use ($firstFileData, $secondFileData) {
        if (array_key_exists($key, $secondFileData)) {
            if ($firstFileData[$key] === $secondFileData[$key]) {
                $d["  {$key}"] = $firstFileData[$key];
            } else {
                $d["- {$key}"] = $firstFileData[$key];
                $d["+ {$key}"] = $secondFileData[$key];
            }
        } else {
            $d["- {$key}"] = $firstFileData[$key];
        }
        return $d;
    }, []);
    $diff = array_reduce(array_keys($secondFileData), function ($d, $key) use ($firstFileData, $secondFileData) {
        if (!array_key_exists($key, $firstFileData)) {
            $d["+ {$key}"] = $secondFileData[$key];
        }
        return $d;
    }, $diff);

    return $diff;
}
