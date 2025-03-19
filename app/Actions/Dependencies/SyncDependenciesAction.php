<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions\Dependencies;

use Illuminate\Support\LazyCollection;
use PackageWizard\Installer\Contracts\DependencyManager;
use PackageWizard\Installer\Enums\DependencyTypeEnum;

class SyncDependenciesAction extends DependencyAction
{
    public const Type = 'type';

    protected function managers(): array
    {
        $toInstall    = $this->filtered(false, false);
        $toInstallDev = $this->filtered(true, false);
        $toRemove     = $this->filtered(false, true);
        $toRemoveDev  = $this->filtered(true, true);

        return [
            [
                'who'      => __('dependency.install', ['name' => $this->type()->value]),
                'when'     => $toInstall->isNotEmpty(),
                'callback' => fn () => $this->service()->add($this->directory(), $toInstall->all()),
            ],
            [
                'who'      => __('dependency.install_dev', ['name' => $this->type()->value]),
                'when'     => $toInstallDev->isNotEmpty(),
                'callback' => fn () => $this->service()->add($this->directory(), $toInstallDev->all(), true),
            ],
            [
                'who'      => __('dependency.remove', ['name' => $this->type()->value]),
                'when'     => $toRemove->isNotEmpty(),
                'callback' => fn () => $this->service()->remove($this->directory(), $toRemove->all()),
            ],
            [
                'who'      => __('dependency.remove_dev', ['name' => $this->type()->value]),
                'when'     => $toRemoveDev->isNotEmpty(),
                'callback' => fn () => $this->service()->remove($this->directory(), $toRemoveDev->all(), true),
            ],
        ];
    }

    protected function filtered(bool $dev, bool $remove): LazyCollection
    {
        return $this->config()->dependencies
            ->lazy()
            ->where('type', $this->type())
            ->where('remove', $remove)
            ->where('dev', $dev)
            ->pluck('name');
    }

    protected function service(): DependencyManager
    {
        return match ($this->type()) {
            DependencyTypeEnum::Composer => $this->composer(),
            DependencyTypeEnum::Npm      => $this->npm(),
            DependencyTypeEnum::Yarn     => $this->yarn(),
        };
    }

    protected function type(): DependencyTypeEnum
    {
        return $this->parameter(static::Type);
    }
}
