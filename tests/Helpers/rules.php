<?php

declare(strict_types=1);

function rule(string $filename): array
{
    $content = file_get_contents(__DIR__ . "/../Fixtures/Rules/$filename.json");

    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}
