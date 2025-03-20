<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertDirectoryDoesNotExist;
use function PHPUnit\Framework\assertDirectoryExists;

it('installed', function () {
    prepare_project('dependencies-install-npm');

    artisan(NewCommand::class)
        ->expectsConfirmation(__('info.install_dependencies'), 'yes')
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'composer']))
        ->expectsOutputToContain(__('dependency.install', ['name' => 'npm']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'yarn']))
        ->assertSuccessful();

    assertDirectoryDoesNotExist(temp_path('vendor'));
    assertDirectoryExists(temp_path('node_modules'));
    assertDirectoryDoesNotExist(temp_path('.yarn'));
});

it('not installed', function () {
    prepare_project('dependencies-install-npm');

    artisan(NewCommand::class)
        ->expectsConfirmation(__('info.install_dependencies'))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'composer']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'npm']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'yarn']))
        ->assertSuccessful();

    assertDirectoryDoesNotExist(temp_path('vendor'));
    assertDirectoryDoesNotExist(temp_path('node_modules'));
    assertDirectoryDoesNotExist(temp_path('.yarn'));
});
