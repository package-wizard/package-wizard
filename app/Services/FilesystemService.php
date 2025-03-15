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

    public function content(string $filename): string
    {
        return $this->filesystem->get($filename);
    }

    public function store(string $filename, string $content): void
    {
        $this->filesystem->put($filename, $content);
    }
}
