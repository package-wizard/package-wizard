<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('auto', function () {
    prepare_project('questions-Author');

    artisan(NewCommand::class)
        ->expectsQuestion('Some question #1', 'a1')
        ->doesntExpectOutputToContain('Some question #2')
        ->expectsConfirmation('Do you confirm generation?', 'yes')
        ->assertSuccessful();
});

it('manual', function () {
    prepare_project('questions-Author-Manual');

    artisan(NewCommand::class)
        ->expectsQuestion('What is your name?', 'Ivan Ivanov')
        ->expectsQuestion('What is your email?', 'ivan@example.com')
        ->doesntExpectOutputToContain('Some question #1')
        ->expectsQuestion('Some question #2', 'a2')
        ->expectsConfirmation('Do you confirm generation?', 'yes')
        ->assertSuccessful();
});
