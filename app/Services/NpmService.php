<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use PackageWizard\Installer\Support\Npm;

use function implode;
use function vsprintf;

class NpmService
{
    public function __construct(
        protected ProcessService $process,
        protected Npm $npm,
    ) {}

    public function install(string $directory): void
    {
        $command = vsprintf('%s install %s %s', [
            $this->npm->find(),
            $directory,
            $this->options(),
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    public function require(string $directory, iterable $packages, bool $dev = false): void
    {
        $names = collect($packages)->join(' ');

        $command = vsprintf('%s install %s %s %s', [
            $this->npm->find(),
            $names,
            $dev ? '--save-dev' : '--save',
            $this->options(),
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    public function remove(string $directory, iterable $packages, bool $dev = false): void
    {
        $names = collect($packages)->join(' ');

        $command = vsprintf('%s uninstall %s %s %s', [
            $this->npm->find(),
            $names,
            $dev ? '--save-dev' : '--save',
            $this->options(),
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    protected function options(): string
    {
        return implode(' ', [
            '--ignore-scripts',
            '--no-audit',
        ]);
    }
}
