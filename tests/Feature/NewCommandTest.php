<?php

declare(strict_types=1);

use Symfony\Component\Console\Command\Command;

it('new artisans', function () {
    expect(artisan('new'))->toBe(
        Command::SUCCESS
    );
});
