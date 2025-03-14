<?php

namespace PackageWizard\Installer\Data;

use Spatie\LaravelData\Data;

class WizardData extends Data
{
    public bool $install = true;

    public bool $clean = true;
}
