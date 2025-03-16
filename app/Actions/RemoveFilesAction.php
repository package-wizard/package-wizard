<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

class RemoveFilesAction extends Action
{
    protected function title(): string
    {
        return 'Removing files and folders...';
    }

    protected function perform(): void
    {
        $this->config()->removes->each(
            fn (string $path) => $this->remove($this->directory(), $path)
        );
    }

    protected function remove(string $directory, string $path): void
    {
        $this->filesystem->delete($directory . '/' . $path);
    }
}
