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

    /**
     * @return array<\Symfony\Component\Finder\SplFileInfo>
     */
    public function allFiles(string $directory): array
    {
        return $this->filesystem->allFiles($directory);
    }

    public function names(string $directory): array
    {
        $names = [];

        foreach ($this->filesystem->allFiles($directory) as $file) {
            $names[] = $file->getRelativePathname();
        }

        return $names;
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
            Directory::ensureDirectory(dirname($target));
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

    public function exists(string $path): bool
    {
        return $this->filesystem->exists($path);
    }

    public function directoryExists(string $path): bool
    {
        return Directory::exists($path);
    }

    public function directoryIsEmpty(string $path): bool
    {
        return $this->filesystem->isEmptyDirectory($path);
    }

    public function canCreateProject(string $path): bool
    {
        return ! $this->directoryExists($path) || $this->directoryIsEmpty($path);
    }
}
