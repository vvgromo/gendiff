<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\Generator\generateDiff;

class GeneratorTest extends TestCase
{
    public function testGenerateDiffFlatJson()
    {
        $pathBefore = __DIR__ . "/fixtures/before.json";
        $pathAfter = __DIR__ . "/fixtures/after.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedDiffFlat.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffFlatYaml()
    {
        $pathBefore = __DIR__ . "/fixtures/before.yaml";
        $pathAfter = __DIR__ . "/fixtures/after.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedDiffFlat.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }
}