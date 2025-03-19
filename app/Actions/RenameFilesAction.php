<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use Illuminate\Support\Str;
use PackageWizard\Installer\Data\RenameData;
use PackageWizard\Installer\Enums\RenameEnum;

class RenameFilesAction extends Action
{
    protected function title(): string
    {
        return __('info.renaming_files');
    }

    protected function perform(): void
    {
        foreach ($this->files() as $path) {
            $basename = $this->rename(
                $this->basename($path)
            );

            $this->filesystem->rename($path, $this->directory() . '/' . $basename);
        }
    }

    protected function rename(string $basename): string
    {
        foreach ($this->config()->renames as $rename) {
            $basename = match ($rename->what) {
                RenameEnum::Path => $this->forPath($basename, $rename),
                RenameEnum::Name => $this->forName($basename, $rename),
            };
        }

        return $basename;
    }

    protected function forPath(string $basename, RenameData $rename): string
    {
        if ($basename === $rename->source) {
            return $rename->target;
        }

        return $basename;
    }

    protected function forName(string $basename, RenameData $rename): string
    {
        return Str::of($basename)
            ->explode('/')
            ->map(static fn (string $name) => $name === $rename->source ? $rename->target : $name)
            ->join('/');
    }

    protected function basename(string $path): string
    {
        return Str::of(realpath($path))
            ->after(realpath($this->directory()))
            ->replace('\\', '/')
            ->ltrim('/')
            ->toString();
    }
}
