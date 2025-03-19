<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Services\ReplaceService;

use function app;

class ReplaceContentAction extends Action
{
    protected function title(): string
    {
        return __('info.replacing_content');
    }

    protected function perform(): void
    {
        foreach ($this->files() as $filename) {
            $this->replacer()->replace($filename, $this->config()->replaces);
        }
    }

    protected function replacer(): ReplaceService
    {
        return app(ReplaceService::class);
    }
}
