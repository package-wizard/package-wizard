<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions\Dependencies;

use PackageWizard\Installer\Actions\Action;
use PackageWizard\Installer\Services\Managers\ComposerService;
use PackageWizard\Installer\Services\Managers\NpmService;
use PackageWizard\Installer\Services\Managers\YarnService;

use function __;

abstract class DependencyAction extends Action
{
    abstract protected function managers(): array;

    protected function perform(): void {}

    protected function start(): void
    {
        foreach ($this->managers() as $manager) {
            if (! $manager['when']) {
                static::verboseWriteln(__('info.skip', ['name' => $manager['who']]));

                continue;
            }

            static::verbose() || $this->rawOutput
                ? $this->progress($manager['callback'], $manager['who'])
                : $this->spin($manager['callback'], $manager['who']);
        }
    }

    protected function composer(): ComposerService
    {
        return app(ComposerService::class);
    }

    protected function npm(): NpmService
    {
        return app(NpmService::class);
    }

    protected function yarn(): YarnService
    {
        return app(YarnService::class);
    }
}
