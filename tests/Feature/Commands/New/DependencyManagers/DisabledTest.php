<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PHPUnit\Framework\assertFileDoesNotExist;

it('not installed', function () {
    prepare_project('dependencies-install-disabled');

    artisan(NewCommand::class)
        ->doesntExpectOutputToContain(__('info.install_dependencies'))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'composer']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'npm']))
        ->doesntExpectOutputToContain(__('dependency.install', ['name' => 'yarn']))
        ->assertSuccessful();

    assertFileDoesNotExist(temp_path('composer.lock'));
    assertFileDoesNotExist(temp_path('package-lock.json'));
    assertFileDoesNotExist(temp_path('yarn.lock'));
});
