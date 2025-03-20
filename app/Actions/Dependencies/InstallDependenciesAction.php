<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions\Dependencies;

use function __;

class InstallDependenciesAction extends DependencyAction
{
    protected function managers(): array
    {
        return [
            [
                'who'      => __('dependency.install', ['name' => 'composer']),
                'when'     => $this->config()->wizard?->manager?->composer ?? true,
                'callback' => fn () => $this->composer()->install($this->directory()),
            ],
            [
                'who'      => __('dependency.install', ['name' => 'npm']),
                'when'     => $this->config()->wizard?->manager?->npm ?? false,
                'callback' => fn () => $this->npm()->install($this->directory()),
            ],
            [
                'who'      => __('dependency.install', ['name' => 'yarn']),
                'when'     => $this->config()->wizard?->manager?->yarn ?? false,
                'callback' => fn () => $this->yarn()->install($this->directory()),
            ],
        ];
    }
}
