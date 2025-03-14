<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use function realpath;
use function vsprintf;

class HttpService
{
    public function __construct(
        protected ProcessService $process
    ) {}

    public function download(string $name, string $path, bool $dev): string
    {
        $composer = new ComposerService($path);

        $command = $this->createProjectCommand($composer->find(), $name, $path, $dev);

        $this->process->run($command, $path);

        return realpath($path);
    }

    protected function createProjectCommand(string $composer, string $name, string $path, bool $dev): string
    {
        return vsprintf('%s create-project %s "%s" %s --remove-vcs --prefer-dist --no-scripts', [
            $composer,
            $name,
            $path,
            $dev ? '--stability=dev' : '',
        ]);
    }
}
