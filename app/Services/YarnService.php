<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use PackageWizard\Installer\Support\Yarn;

class YarnService
{
    public function __construct(
        protected ProcessService $process,
        protected Yarn $yarn,
    ) {}

    public function install(string $directory): void
    {
        $command = vsprintf('%s install %s', [
            $this->yarn->find(),
            $directory,
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    public function require(string $directory, iterable $packages, bool $dev = false): void
    {
        $names = collect($packages)->join(' ');

        $command = vsprintf('%s add %s %s', [
            $this->yarn->find(),
            $names,
            $dev ? '--dev' : '',
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    public function remove(string $directory, iterable $packages, bool $dev = false): void
    {
        $names = collect($packages)->join(' ');

        $command = vsprintf('%s remove %s %s', [
            $this->yarn->find(),
            $names,
            $dev ? '--dev' : '',
        ]);

        $this->process->runWithInteract($command, $directory);
    }
}
