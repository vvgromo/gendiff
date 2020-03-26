<?php

namespace Gendiff\Formatters\Plain;

function renderJson($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}
