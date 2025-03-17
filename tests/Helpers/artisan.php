<?php

declare(strict_types=1);

use Illuminate\Testing\PendingCommand;

function artisan(string $command, array $parameters = []): PendingCommand
{
    return test()->artisan($command, array_merge([
        'name'    => temp_path(),
        '--local' => true,
    ], $parameters));
}
