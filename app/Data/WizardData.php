<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;

class WizardData extends Data
{
    public WizardInstallData $install;

    public bool $clean;

    public static function prepareForPipeline(array $properties): array
    {
        $properties['install'] ??= [];

        return $properties;
    }
}
