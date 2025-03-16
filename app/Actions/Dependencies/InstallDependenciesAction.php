<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions\Dependencies;

class InstallDependenciesAction extends DependencyAction
{
    protected function title(): string
    {
        return 'Dependency installing...';
    }

    protected function managers(): array
    {
        return [
            [
                'who'      => '[composer] Dependency installation...',
                'when'     => $this->config()->wizard?->install?->composer ?? true,
                'callback' => fn () => $this->composer()->install($this->directory()),
            ],
            [
                'who'      => '[npm] Dependency installation...',
                'when'     => $this->config()->wizard?->install?->npm ?? false,
                'callback' => fn () => $this->npm()->install($this->directory()),
            ],
            [
                'who'      => '[yarn] Dependency installation...',
                'when'     => $this->config()->wizard?->install?->yarn ?? false,
                'callback' => fn () => $this->yarn()->install($this->directory()),
            ],
        ];
    }
}
