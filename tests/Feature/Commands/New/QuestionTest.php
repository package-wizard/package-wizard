<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\File;
use PackageWizard\Installer\Commands\NewCommand;

use function PackageWizard\Installer\resource_path;
use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('questions', function () {
    prepare_project('questions');

    $licenses = File::names(resource_path('licenses'));

    artisan(NewCommand::class)
        // The first attempt
        ->expectsChoice('Which is license will be distributed?', 'Boost Software 1', $licenses)
        ->expectsQuestion('What is your email?', 'qwe@example.com')
        ->expectsQuestion('Replace namespace', 'Qwe\\Rty')
        ->expectsChoice('Replace description', 'baz', ['foo', 'bar', 'baz'])
        ->expectsConfirmation('Do you confirm generation?')
        // The second attempt
        ->expectsChoice('Which is license will be distributed?', 'Apache License 2', $licenses)
        ->expectsQuestion('What is your email?', 'some@example.com')
        ->expectsQuestion('Replace namespace', 'Foo\\Bar')
        ->expectsChoice('Replace description', 'bar', ['foo', 'bar', 'baz'])
        ->expectsConfirmation('Do you confirm generation?', 'yes')
        ->assertSuccessful();
});
