<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $extension)
{
    $parseYaml = function ($data) {
        return Yaml::parse($data);
    };
    $result = [
        'json' => function ($data) {
            return json_decode($data, true);
        },
        'yaml' => $parseYaml,
        'yml' => $parseYaml
    ];

    return $result[$extension]($data);
}
