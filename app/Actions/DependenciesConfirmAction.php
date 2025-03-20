<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use PackageWizard\Installer\Contracts\DependencyManager;
use PackageWizard\Installer\Services\Managers\ComposerService;
use PackageWizard\Installer\Services\Managers\NpmService;
use PackageWizard\Installer\Services\Managers\YarnService;

use function app;
use function Laravel\Prompts\confirm;

class DependenciesConfirmAction extends Action
{
    protected bool $rawOutput = true;

    protected function perform(): void
    {
        $this->prepare();
        $this->collision();

        if (! $this->needToInstall() || ! $this->confirm()) {
            $this->disable();
        }
    }

    protected function prepare(): void
    {
        $this->config()->wizard->manager->composer = $this->canComposer();
        $this->config()->wizard->manager->npm      = $this->canNpm();
        $this->config()->wizard->manager->yarn     = $this->canYarn();
    }

    protected function collision(): void
    {
        if ($this->canNpm() && $this->canYarn()) {
            $this->config()->wizard->manager->yarn = false;
        }
    }

    protected function confirm(): bool
    {
        return confirm(__('info.install_dependencies'));
    }

    protected function disable(): void
    {
        $this->config()->wizard->manager->composer = false;
        $this->config()->wizard->manager->npm      = false;
        $this->config()->wizard->manager->yarn     = false;
    }

    protected function needToInstall(): bool
    {
        return $this->config()->wizard->manager->composer
            || $this->config()->wizard->manager->npm
            || $this->config()->wizard->manager->yarn;
    }

    protected function canComposer(): bool
    {
        return $this->config()->wizard->manager->composer
            && $this->exists($this->composer());
    }

    protected function canNpm(): bool
    {
        return $this->config()->wizard->manager->npm
            && $this->exists($this->npm());
    }

    protected function canYarn(): bool
    {
        return $this->config()->wizard->manager->yarn
            && $this->exists($this->yarn());
    }

    protected function exists(DependencyManager $manager): bool
    {
        return $this->filesystem->exists(
            $this->config()->directory . '/' . $manager->filename()
        );
    }

    protected function composer(): DependencyManager
    {
        return app(ComposerService::class);
    }

    protected function npm(): DependencyManager
    {
        return app(NpmService::class);
    }

    protected function yarn(): DependencyManager
    {
        return app(YarnService::class);
    }
}
