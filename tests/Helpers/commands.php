<?php

declare(strict_types=1);

use Symfony\Component\Console\Tester\CommandTester;

function tester(string $command): CommandTester
{
    return new CommandTester(
        createApplication()->find($command)
    );
}

function artisan(string $command, array $arguments = [], array $options = []): int
{
    return tester($command)->execute($arguments, array_merge($options, [
        'interactive' => false,
    ]));
}
