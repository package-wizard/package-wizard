<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('conditions', function () {
    prepare_project('questions-License');

    artisan(NewCommand::class)
        ->expectsQuestion('Which is license will be distributed?', 'Boost Software 1')
        ->doesntExpectOutputToContain('Some question #1')
        ->expectsQuestion('Some question #2', 'a2')
        ->expectsConfirmation('Do you confirm generation?', 'yes')
        ->assertSuccessful();
});
