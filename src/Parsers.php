<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $format)
{
    $result = [
        'json' => function ($data) {
            return json_decode($data, true);
        },
        'yaml' => function ($data) {
            return Yaml::parse($data);
        }
    ];

    return $result[$format]($data);
}