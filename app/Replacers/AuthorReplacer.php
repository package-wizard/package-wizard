<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Replacers;

use Illuminate\Support\Str;

/** @property \PackageWizard\Installer\Data\AuthorData $data */
class AuthorReplacer extends Replacer
{
    protected function with(): string
    {
        return Str::replace(
            [':name:', ':email:'],
            [$this->data->name, $this->data->email],
            $this->data->format
        );
    }
}
