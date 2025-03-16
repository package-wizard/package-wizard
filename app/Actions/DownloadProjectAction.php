<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Services\Managers\ComposerService;

use function app;

class DownloadProjectAction extends Action
{
    public const Dev     = 'dev';
    public const Package = 'package';
    public const Version = 'version';

    protected function title(): string
    {
        return 'Downloading the project...';
    }

    protected function perform(): void
    {
        $this->composer()->createProject(
            $this->directory(),
            $this->parameter(static::Package),
            $this->parameter(static::Version),
            $this->parameter(static::Dev),
        );
    }

    protected function composer(): ComposerService
    {
        return app(ComposerService::class);
    }
}
