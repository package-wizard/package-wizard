<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

/** @property \PackageWizard\Installer\Data\ReplaceData $data */
class LicenseReplacer extends Replacer
{
    protected function with(): int|string
    {
        return $this->data->with;
    }
}
