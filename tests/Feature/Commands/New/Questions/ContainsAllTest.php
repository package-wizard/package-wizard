<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('conditions', function () {
    prepare_project('questions-ContainsAll');

    artisan(NewCommand::class)
        ->expectsQuestion('Target question', '500')
        ->expectsQuestion('Some question #1', 'a1')
        ->expectsQuestion('Some question #2', 'a2')
        ->doesntExpectOutputToContain('Some question #3')
        ->doesntExpectOutputToContain('Some question #4')
        ->expectsConfirmation(__('info.accept'), 'yes')
        ->assertSuccessful();
});
