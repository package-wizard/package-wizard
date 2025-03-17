<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions\Dependencies;

use Illuminate\Support\LazyCollection;
use PackageWizard\Installer\Contracts\DependencyManager;
use PackageWizard\Installer\Enums\DependencyTypeEnum;

use function sprintf;

class SyncDependenciesAction extends DependencyAction
{
    public const Type = 'type';

    protected function managers(): array
    {
        $toInstall    = $this->toInstall();
        $toInstallDev = $this->toInstallDev();
        $toRemove     = $this->toRemove();
        $toRemoveDev  = $this->toRemoveDev();

        return [
            [
                'who'      => sprintf('[%s] Dependency installation...', $this->type()->value),
                'when'     => $toInstall->isNotEmpty(),
                'callback' => fn () => $this->service()->add($this->directory(), $toInstall->all()),
            ],
            [
                'who'      => sprintf('[%s] Installing dev dependencies...', $this->type()->value),
                'when'     => $toInstallDev->isNotEmpty(),
                'callback' => fn () => $this->service()->add($this->directory(), $toInstallDev->all(), true),
            ],
            [
                'who'      => sprintf('[%s] Removing dependencies...', $this->type()->value),
                'when'     => $toRemove->isNotEmpty(),
                'callback' => fn () => $this->service()->remove($this->directory(), $toRemove->all()),
            ],
            [
                'who'      => sprintf('[%s] Removing dev dependencies...', $this->type()->value),
                'when'     => $toRemoveDev->isNotEmpty(),
                'callback' => fn () => $this->service()->remove($this->directory(), $toRemoveDev->all(), true),
            ],
        ];
    }

    protected function toInstall(): LazyCollection
    {
        return $this->config()->dependencies
            ->lazy()
            ->where('type', $this->type())
            ->where('remove', false)
            ->where('dev', false)
            ->pluck('name');
    }

    protected function toInstallDev(): LazyCollection
    {
        return $this->config()->dependencies
            ->lazy()
            ->where('type', $this->type())
            ->where('remove', false)
            ->where('dev', true)
            ->pluck('name');
    }

    protected function toRemove(): LazyCollection
    {
        return $this->config()->dependencies
            ->lazy()
            ->where('type', $this->type())
            ->where('remove', true)
            ->where('dev', false)
            ->pluck('name');
    }

    protected function toRemoveDev(): LazyCollection
    {
        return $this->config()->dependencies
            ->lazy()
            ->where('type', $this->type())
            ->where('remove', true)
            ->where('dev', true)
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
