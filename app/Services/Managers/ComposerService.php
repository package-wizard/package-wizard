<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services\Managers;

use Illuminate\Support\Composer;
use PackageWizard\Installer\Support\Console;

use function collect;
use function implode;

class ComposerService extends Manager
{
    protected array $options = [
        '--ignore-platform-reqs',
        '--no-scripts',
        '--prefer-dist',
    ];

    public function __construct(
        protected Composer $composer,
    ) {}

    public function createProject(string $directory, string $package, ?string $version, bool $dev): void
    {
        $command = vsprintf('%s create-project %s "%s" %s %s %s', [
            $this->find(),
            $package,
            $directory,
            $version,
            $this->options([
                '--no-install',
                '--stability=dev'  => $dev,
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory);
    }

    public function install(string $directory): void
    {
        $command = vsprintf('%s update %s %s', [
            $this->find(),
            $this->options([
                '--working-dir'    => $directory,
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory);
    }

    public function add(string $directory, iterable $packages, bool $dev = false): void
    {
        $command = vsprintf('%s require %s %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--working-dir'    => $directory,
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),

                '--dev' => $dev,
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory);
    }

    public function remove(string $directory, iterable $packages, bool $dev = false): void
    {
        $command = vsprintf('%s require %s %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--working-dir'    => $directory,
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),

                '--dev' => $dev,
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory);
    }

    protected function ansi(): string
    {
        return Console::ansi() ? '--ansi' : '--no-ansi';
    }

    protected function find(): string
    {
        return implode(' ', $this->composer->findComposer());
    }
}
