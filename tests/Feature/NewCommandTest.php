<?php

declare(strict_types=1);

use PackageWizard\Installer\Commands\NewCommand;

use function PackageWizard\Installer\base_path;

it('new artisans', function () {
    prepare_project('auto');

    $this->artisan(NewCommand::class, [
        'name'    => base_path('tests/Fixtures/temp'),
        '--local' => true,
    ])->assertSuccessfull();
});
