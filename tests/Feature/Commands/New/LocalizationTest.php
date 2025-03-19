<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

it('option', function (string $locale) {
    prepare_project('localization-option');

    artisan(NewCommand::class, ['--lang' => $locale])
        ->expectsQuestion(__('form.field.license', locale: $locale), 'Boost Software 1')
        ->expectsConfirmation(__('info.accept', locale: $locale), 'yes')
        ->assertSuccessful();
})->with('localization');

it('schema', function () {
    prepare_project('localization-schema');

    $locale = 'de';

    artisan(NewCommand::class)
        ->expectsQuestion(__('form.field.license', locale: $locale), 'Boost Software 1')
        ->expectsConfirmation(__('info.accept', locale: $locale), 'yes')
        ->assertSuccessful();
});

it('overwrite', function () {
    prepare_project('localization-schema');

    $locale = 'fr';

    artisan(NewCommand::class, ['--lang' => $locale])
        ->expectsQuestion(__('form.field.license', locale: $locale), 'Boost Software 1')
        ->expectsConfirmation(__('info.accept', locale: $locale), 'yes')
        ->assertSuccessful();
});
