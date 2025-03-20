<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services\Managers;

use Illuminate\Support\Composer;
use PackageWizard\Installer\Support\Console;

use function collect;

class ComposerService extends Manager
{
    protected array $options = [
        '--ignore-platform-reqs' => true,
        '--no-scripts'           => true,
        '--prefer-dist'          => true,
    ];

    public function __construct(
        protected Composer $composer,
    ) {}

    public function find(): string
    {
        return implode(' ', $this->composer->findComposer());
    }

    public function filename(): string
    {
        return 'composer.json';
    }

    public function install(string $directory): void
    {
        $command = vsprintf('%s update %s %s', [
            $this->find(),
            $this->options([
                '--working-dir'    => $this->spaced($directory),
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory, false);
    }

    public function add(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s require %s %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--working-dir'    => $this->spaced($directory),
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),
                '--no-install',

                '--dev' => $dev,
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory, false);
    }

    public function remove(string $directory, array $packages, bool $dev = false): void
    {
        $command = vsprintf('%s remove %s %s %s', [
            $this->find(),
            collect($packages)->join(' '),
            $this->options([
                '--working-dir'    => $this->spaced($directory),
                '--quiet'          => Console::quiet(),
                '--no-interaction' => Console::quiet(),
                '--prefer-dist'    => false,
                '--no-install',

                '--dev' => $dev,
            ]),
            $this->ansi(),
        ]);

        $this->perform($command, $directory, false);
    }

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

        $this->perform($command, $directory, false);
    }

    protected function ansi(): string
    {
        return Console::ansi() ? '--ansi' : '--no-ansi';
    }
}
