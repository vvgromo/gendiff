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
        $actual = generateDiff($pathBefore, $pathAfter, 'pretty');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffFlatYaml()
    {
        $pathBefore = __DIR__ . "/fixtures/before.yaml";
        $pathAfter = __DIR__ . "/fixtures/after.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedFlatDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'pretty');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedJson()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.json";
        $pathAfter = __DIR__ . "/fixtures/afterNested.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'pretty');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedYaml()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.yaml";
        $pathAfter = __DIR__ . "/fixtures/afterNested.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'pretty');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedJsonFormatPlain()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.json";
        $pathAfter = __DIR__ . "/fixtures/afterNested.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiffPlain.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'plain');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedYamlFormatPlain()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.yaml";
        $pathAfter = __DIR__ . "/fixtures/afterNested.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiffPlain.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'plain');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedJsonFormatJson()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.json";
        $pathAfter = __DIR__ . "/fixtures/afterNested.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiffJson.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'json');
        
        $this->assertEquals($expected, $actual);
    }

    public function testGenerateDiffNestedYamlFormatJson()
    {
        $pathBefore = __DIR__ . "/fixtures/beforeNested.yaml";
        $pathAfter = __DIR__ . "/fixtures/afterNested.yaml";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedNestedDiffJson.txt");
        $actual = generateDiff($pathBefore, $pathAfter, 'json');
        
        $this->assertEquals($expected, $actual);
    }
}