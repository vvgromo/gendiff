<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\Generator\generateDiff;

class GeneratorTest extends TestCase
{
    public function testGenerateDiff()
    {
        $pathBefore = __DIR__ . "/fixtures/before.json";
        $pathAfter = __DIR__ . "/fixtures/after.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/expected/expectedDiff.txt");
        $actual = generateDiff($pathBefore, $pathAfter);
        
        $this->assertEquals($expected, $actual);
    }
}