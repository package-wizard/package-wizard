<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

it('auto', function (string $locale) {
    prepare_project('localization');

    artisan(NewCommand::class, ['--lang' => $locale])
        ->expectsQuestion(__('form.field.license', locale: $locale), 'Boost Software 1')
        ->expectsConfirmation(__('info.accept', locale: $locale), 'yes')
        ->assertSuccessful();
})->with('localization');
