<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Generator\generateDiff;
use function Gendiff\Utils\pathJoin;

class GeneratorTest extends TestCase
{
    public function addDataProvider()
    {
        return [
            ['before.json', 'after.json', 'expectedDiff.txt', 'pretty'],
            ['before.yaml', 'after.yaml', 'expectedDiff.txt', 'pretty'],
            ['before.json', 'after.json', 'expectedDiffPlain.txt', 'plain'],
            ['before.yaml', 'after.yaml', 'expectedDiffPlain.txt', 'plain'],
            ['before.json', 'after.json', 'expectedDiffJson.json', 'json'],
            ['before.yaml', 'after.yaml', 'expectedDiffJson.json', 'json']
        ];
    }

    /**
     * @dataProvider addDataProvider
     */
    public function testGenerateDiff($fileNameBefore, $fileNameAfter, $fileNameExp, $format)
    {
        $getfixturePath = function ($filePath) {
            return pathJoin(__DIR__, 'fixtures', $filePath);
        };
        $beforePath = $getfixturePath($fileNameBefore);
        $afterPath = $getfixturePath($fileNameAfter);
        $expected = file_get_contents($getfixturePath(pathJoin('expected', $fileNameExp)));
        $actual = generateDiff($beforePath, $afterPath, $format);
        
        $this->assertEquals($expected, $actual);
    }
}
