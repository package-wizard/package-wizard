<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services\Managers;

use PackageWizard\Installer\Support\Yarn;

class YarnService extends Manager
{
    public function __construct(
        protected Yarn $yarn,
    ) {}

    public function install(string $directory): void
    {
        $command = vsprintf('%s install %s', [
            $this->yarn->find(),
            $directory,
        ]);

        $this->perform($command, $directory);
    }

    public function add(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s add %s %s', [
            $this->yarn->find(),
            collect($packages)->join(' '),
            $dev ? '--dev' : '',
        ]);

        $this->perform($command, $directory);
    }

    public function remove(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s remove %s %s', [
            $this->yarn->find(),
            collect($packages)->join(' '),
            $dev ? '--dev' : '',
        ]);

        $this->perform($command, $directory);
    }
}
