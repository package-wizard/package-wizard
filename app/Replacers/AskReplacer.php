<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

use PackageWizard\Installer\Data\ReplaceData;

/** @property ReplaceData $data */
class AskReplacer extends Replacer
{
    protected function make(): ReplaceData
    {
        $this->data->asked = $this->asked;

        return $this->data;
    }

    protected function with(): int|string
    {
        return 0;
    }
}
