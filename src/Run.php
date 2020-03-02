<?php

namespace Gendiff\Run;

use function Gendiff\Generator\generateDiff;
use Docopt;

function run()
{
    $doc = <<<DOC
      Generate diff

      Usage:
        gendiff (-h|--help)
        gendiff (-v|--version)
        gendiff [--format <fmt>] <firstFile> <secondFile>

      Options:
        -h --help                     Show this screen
        -v --version                  Show version
        --format <fmt>                Report format [default: pretty]

DOC;

    $args = Docopt::handle($doc);
    $filePath1 = $args["<firstFile>"];
    $filePath2 = $args["<secondFile>"];
    echo generateDiff($filePath1, $filePath2) . PHP_EOL;
}
