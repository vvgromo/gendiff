<?php

namespace Gendiff\Generator;

function generateDiff($filePath1, $filePath2)
{
    if (!file_exists($filePath1)) {
        $filePath1 = __DIR__ . '/' . $filePath1;
        $filePath2 = __DIR__ . '/' . $filePath2;
    }
    $firstFileStr = file_get_contents($filePath1);
    $secondFileStr = file_get_contents($filePath2);
    $firstFileData = json_decode($firstFileStr, true);
    $secondFileData = json_decode($secondFileStr, true);
    $jsonDiff = createJsonDiff($firstFileData, $secondFileData);    
    $result = json_encode($jsonDiff, JSON_PRETTY_PRINT);

    return str_replace(['"', ','], "", $result);
}

function createJsonDiff($firstFileData, $secondFileData)
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