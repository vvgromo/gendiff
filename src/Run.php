<?php

namespace Gendiff\Run;

use Docopt;

use function Gendiff\Generator\generateDiff;

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
    $format = $args["--format"];
    echo generateDiff($filePath1, $filePath2, $format) . PHP_EOL;
}
