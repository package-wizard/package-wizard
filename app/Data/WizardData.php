<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;

class WizardData extends Data
{
    public WizardInstallData $manager;

    public bool $clean = true;

    public static function prepareForPipeline(array $properties): array
    {
        $properties['manager'] ??= [];

        return $properties;
    }
}
