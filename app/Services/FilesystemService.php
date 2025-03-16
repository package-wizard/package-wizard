<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Filesystem\Filesystem;

use function dirname;
use function is_dir;

class FilesystemService
{
    public function __construct(
        protected Filesystem $filesystem
    ) {}

    public function allFiles(string $directory): array
    {
        return File::allPaths($directory, recursive: true);
    }

    public function content(string $path): string
    {
        return $this->filesystem->get($path);
    }

    public function store(string $path, string $content): void
    {
        $this->filesystem->put($path, $content);
    }

    public function rename(string $source, string $target): void
    {
        $this->filesystem->move($source, $target);
    }

    public function copy(string $source, string $target): void
    {
        if (! $this->filesystem->exists($source)) {
            return;
        }

        $this->delete($target);

        if ($this->filesystem->isFile($source)) {
            $this->filesystem->ensureDirectoryExists(dirname($target));
        }

        $this->filesystem->isFile($source)
            ? $this->filesystem->copy($source, $target)
            : $this->filesystem->copyDirectory($source, $target);
    }

    public function remove(string $path): void
    {
        is_dir($path)
            ? Directory::ensureDelete($path)
            : File::ensureDelete($path);
    }

    public function delete(string $path): void
    {
        $this->filesystem->isFile($path)
            ? File::ensureDelete($path)
            : Directory::ensureDelete($path);
    }
}
