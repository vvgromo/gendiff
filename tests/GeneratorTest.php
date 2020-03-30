<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Generator\generateDiff;

class GeneratorTest extends TestCase
{
    public function addDataProvider()
    {
        return array(
            array('before.json', 'after.json', 'expectedDiff.txt', 'pretty'),
            array('before.yaml', 'after.yaml', 'expectedDiff.txt', 'pretty'),
            array('before.json', 'after.json', 'expectedDiffPlain.txt', 'plain'),
            array('before.yaml', 'after.yaml', 'expectedDiffPlain.txt', 'plain'),
            array('before.json', 'after.json', 'expectedDiffJson.json', 'json'),
            array('before.yaml', 'after.yaml', 'expectedDiffJson.json', 'json')
        );
    }


    /**
     * @dataProvider addDataProvider
     */
    public function testGenerateDiff($fileBefore, $fileAfter, $fileExp, $format)
    {
        $pathBefore = __DIR__ . "/fixtures/{$fileBefore}";
        $pathAfter = __DIR__ . "/fixtures/{$fileAfter}";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/{$fileExp}");
        $actual = generateDiff($pathBefore, $pathAfter, $format);
        
        $this->assertEquals($expected, $actual);
    }
}
