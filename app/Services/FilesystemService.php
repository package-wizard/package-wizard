<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Filesystem\Filesystem;

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
}
