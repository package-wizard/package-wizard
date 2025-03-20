<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services\Managers;

class YarnService extends Manager
{
    public function find(): string
    {
        return 'yarn';
    }

    public function filename(): string
    {
        return 'package.json';
    }

    public function install(string $directory): void
    {
        $command = vsprintf('%s install %s', [
            $this->find(),
            $this->options(),
        ]);

        $this->perform($command, $directory);
    }

    public function add(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s add %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--dev' => $dev,
            ]),
        ]);

        $this->perform($command, $directory);
    }

    public function remove(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s remove %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--dev' => $dev,
            ]),
        ]);

        $this->perform($command, $directory);
    }
}
