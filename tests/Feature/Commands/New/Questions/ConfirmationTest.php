<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Helpers\Arr;
use PackageWizard\Installer\Commands\NewCommand;

use function PackageWizard\Installer\resource_path;
use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('questions', function () {
    prepare_project('questions');

    $licenses = Arr::ofFile(resource_path('licenses/list.json'))->toArray();

    artisan(NewCommand::class)
        // The first attempt
        ->expectsChoice(__('Which is license will be distributed?'), 'bsl-1.0', $licenses)
        ->expectsQuestion(__('What is your email?'), 'qwe@example.com')
        ->expectsQuestion('Replace namespace', 'Qwe\\Rty')
        ->expectsChoice('Replace description', 'baz', ['foo', 'bar', 'baz'])
        ->expectsConfirmation(__('info.accept'))
        // The second attempt
        ->expectsChoice(__('Which is license will be distributed?'), 'apache-2.0', $licenses)
        ->expectsQuestion(__('What is your email?'), 'some@example.com')
        ->expectsQuestion('Replace namespace', 'Foo\\Bar')
        ->expectsChoice('Replace description', 'bar', ['foo', 'bar', 'baz'])
        ->expectsConfirmation(__('info.accept'), 'yes')
        ->assertSuccessful();
});
