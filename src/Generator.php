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
    
}
