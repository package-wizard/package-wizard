<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('conditions', function () {
    prepare_project('questions-ExistsPath');

    artisan(NewCommand::class)
        ->doesntExpectOutputToContain('Some question #1')
        ->doesntExpectOutputToContain('Some question #2')
        ->expectsQuestion('Some question #3', 'a3')
        ->expectsQuestion('Some question #4', 'a4')
        ->expectsConfirmation('Do you confirm generation?', 'yes')
        ->assertSuccessful();
});
