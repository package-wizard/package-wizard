<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;
use function PHPUnit\Framework\assertFileExists;

beforeEach(function () {
    prepare_project('dependencies-install-all');
});

it('installed', function () {
    artisan(NewCommand::class)
        ->expectsConfirmation(__('info.install_dependencies'), 'yes')
        ->expectsOutputToContain(__('dependency.install', ['name' => 'composer']))
        ->expectsOutputToContain(__('dependency.install', ['name' => 'npm']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'yarn']))
        ->assertSuccessful();

    assertFileExists(temp_path('composer.lock'));
    assertFileExists(temp_path('package-lock.json'));
    assertFileDoesNotExist(temp_path('yarn.lock'));
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
