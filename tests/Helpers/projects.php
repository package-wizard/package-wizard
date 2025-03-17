<?php

declare(strict_types=1);

use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;

use function PackageWizard\Installer\base_path;

function prepare_project(string $wizard, bool $withWizard = true): void
{
    $path = temp_path();

    Directory::ensureDelete($path);

    Directory::copy(
        base_path('tests/Fixtures/Project'),
        $path
    );

    if ($withWizard) {
        File::copy(
            base_path("tests/Fixtures/Wizards/$wizard.json"),
            $path . '/wizard.json'
        );
    }
}
