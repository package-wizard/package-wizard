<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services\Managers;

use function vsprintf;

class NpmService extends Manager
{
    protected array $options = [
        '--ignore-scripts' => true,
        '--no-audit'       => true,
        '--no-fund'        => true,
    ];

    public function find(): string
    {
        return 'npm';
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
        $command = vsprintf('%s install %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--save-dev' => $dev,
                '--save'     => ! $dev,
            ]),
        ]);

        $this->perform($command, $directory);
    }

    public function remove(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s uninstall %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--save-dev' => $dev,
                '--save'     => ! $dev,
            ]),
        ]);

        $this->perform($command, $directory);
    }
}
