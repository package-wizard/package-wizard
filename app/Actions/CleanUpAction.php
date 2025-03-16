<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use DragonCode\Support\Facades\Filesystem\File;

use function config;

class CleanUpAction extends Action
{
    protected function perform(): void
    {
        File::ensureDelete($this->directory() . '/' . $this->filename());
    }

    protected function filename(): string
    {
        return config('wizard.filename');
    }
}
