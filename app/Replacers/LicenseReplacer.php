<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

/** @property \PackageWizard\Installer\Data\ReplaceData|array|null $data */
class LicenseReplacer extends Replacer
{
    protected function make(): array
    {
        foreach ($this->data as $data) {
            $data->asked = true;
        }

        return $this->data;
    }

    protected function with(): int|string
    {
        return 1;
    }
}
