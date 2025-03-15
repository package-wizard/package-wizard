<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PackageWizard\Installer\Data\ReplaceData;

class ReplaceService
{
    public function __construct(
        protected FilesystemService $filesystem
    ) {}

    /**
     * @param  Collection<ReplaceData>  $replaces
     */
    public function replace(string $filename, Collection $replaces): void
    {
        $content = $this->read($filename);

        foreach ($replaces as $item) {
            $content = Str::replace($item->replace, $item->with, $content);
        }

        $this->store($filename, $content);
    }

    protected function read(string $filename): string
    {
        return $this->filesystem->content($filename);
    }

    protected function store(string $filename, string $content): void
    {
        $this->filesystem->store($filename, $content);
    }
}
