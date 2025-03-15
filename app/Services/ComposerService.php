<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Support\Composer;

use function implode;

readonly class ComposerService
{
    public function __construct(
        protected ProcessService $process,
        protected Composer $composer,
    ) {}

    public function createProject(string $directory, string $package, ?string $version, bool $dev, bool $ansi): void
    {
        $command = vsprintf('%s create-project --no-install %s "%s" %s %s %s', [
            $this->find(),
            $package,
            $directory,
            $version,
            $this->stability($dev),
            $this->options(),
            $this->ansi($ansi),
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    public function update(string $directory, bool $ansi): void
    {
        $command = vsprintf('%s update %s %s', [
            $this->find(),
            $this->options(),
            $this->ansi($ansi),
        ]);

        $this->process->runWithInteract($command, $directory);
    }

    protected function stability(bool $dev): string
    {
        if ($dev) {
            return '--stability=dev';
        }

        return '';
    }

    protected function ansi(bool $enabled): string
    {
        return $enabled ? '--ansi' : '--no-ansi';
    }

    protected function options(): string
    {
        return implode(' ', [
            '--ignore-platform-reqs',
            '--no-scripts',
            '--prefer-dist',
            '--remove-vcs',
        ]);
    }

    protected function find(): string
    {
        return implode(' ', $this->composer->findComposer());
    }
}
