<?php

declare(strict_types=1);

use Symfony\Component\Console\Attribute\AsCommand;

arch()->expect('App\Commands')
    ->toHaveSuffix('Command');

arch()->expect('App\Commands')
    ->toHaveAttribute(AsCommand::class);

arch()->expect('App\Commands')
    ->not->toBeUsedIn(['App']);
