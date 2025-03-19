<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

afterEach(
    fn () => assertFileDoesNotExist(temp_path('wizard.json'))
);

it('validate', function () {
    prepare_project('auto');

    artisan(NewCommand::class)
        ->expectsOutputToContain(__('info.validating_schema'))
        ->assertSuccessful();
});
