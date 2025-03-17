<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services\Managers;

use PackageWizard\Installer\Contracts\DependencyManager;
use PackageWizard\Installer\Services\ProcessService;

use function app;

abstract class Manager implements DependencyManager
{
    protected array $options = [];

    protected function options(array $options = []): string
    {
        return collect($this->options)
            ->merge($options)
            ->map(static function (bool|string $value, int|string $key) {
                if (is_bool($value)) {
                    return $value ? $key : null;
                }

                if (is_string($value) && is_string($key)) {
                    return $key . '=' . $value;
                }

                return $value;
            })
            ->filter()
            ->join(' ');
    }

    protected function perform(string $command, string $directory): void
    {
        app(ProcessService::class)->runWithInteract($command, $directory);
    }

    protected function spaced(string $value): string
    {
        return '"' . $value . '"';
    }
}
