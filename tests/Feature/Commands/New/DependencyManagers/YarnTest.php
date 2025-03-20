<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\File;
use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;
use function PHPUnit\Framework\assertFileExists;

beforeEach(function () {
    prepare_project('dependencies-install-yarn');

    if (env('GITHUB_ACTIONS')) {
        File::ensureDelete(temp_path('yarn.lock'));
    }
});

it('installed', function () {
    artisan(NewCommand::class)
        ->expectsConfirmation(__('info.install_dependencies'), 'yes')
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'composer']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'npm']))
        ->expectsOutputToContain(__('dependency.install', ['name' => 'yarn']))
        ->assertSuccessful();

    assertFileDoesNotExist(temp_path('composer.lock'));
    assertFileDoesNotExist(temp_path('package-lock.json'));
    assertFileExists(temp_path('yarn.lock'));
});

it('not installed', function () {
    artisan(NewCommand::class)
        ->expectsConfirmation(__('info.install_dependencies'))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'composer']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'npm']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'yarn']))
        ->assertSuccessful();

    assertFileDoesNotExist(temp_path('composer.lock'));
    assertFileDoesNotExist(temp_path('package-lock.json'));
    assertFileDoesNotExist(temp_path('yarn.lock'));
});
