<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('default', function () {
    prepare_project('questions-License');

    artisan(NewCommand::class)
        ->expectsQuestion(__('Which is license will be distributed?'), 'bsl-1.0')
        ->doesntExpectOutputToContain('Some question #1')
        ->expectsQuestion('Some question #2', 'a2')
        ->expectsConfirmation(__('info.accept'), 'yes')
        ->assertSuccessful();
});

it('overwrite', function () {
    prepare_project('questions-License-overwrite');

    artisan(NewCommand::class)
        ->expectsQuestion(__('Which is license will be distributed?'), 'bsl-1.0')
        ->expectsConfirmation(__('info.accept'), 'yes')
        ->assertSuccessful();
});
