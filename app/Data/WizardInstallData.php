<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;

class WizardInstallData extends Data
{
    public bool $composer = true;

    public bool $npm = false;

    public bool $yarn = false;
}
