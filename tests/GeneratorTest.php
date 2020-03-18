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
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedFlatDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffFlatYaml()
    {
        $pathBefore = __DIR__ . "/fixtures/before.yaml";
        $pathAfter = __DIR__ . "/fixtures/after.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedFlatDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedJson()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.json";
        $pathAfter = __DIR__ . "/fixtures/afterNested.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedYaml()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.yaml";
        $pathAfter = __DIR__ . "/fixtures/afterNested.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedJsonFormatPretty()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.json";
        $pathAfter = __DIR__ . "/fixtures/afterNested.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiffPretty.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedYamlFormatPretty()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.yaml";
        $pathAfter = __DIR__ . "/fixtures/afterNested.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiffPretty.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }
}