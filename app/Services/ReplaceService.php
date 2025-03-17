<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PackageWizard\Installer\Data\ReplaceData;

use function array_map;
use function is_array;
use function is_string;

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

            if ($this->isBackslash($item->replace) || $this->isBackslash($item->with)) {
                $content = Str::replace(
                    $this->backslashArray($item->replace),
                    $this->backslash($item->with),
                    $content
                );
            }
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

    protected function isBackslash(mixed $value): bool
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                if ($this->isBackslash($item)) {
                    return true;
                }
            }

            return false;
        }

        return is_string($value) && Str::contains($value, '\\');
    }

    protected function backslashArray(array $values): array
    {
        return array_map(fn (int|string $value) => $this->backslash($value), $values);
    }

    protected function backslash(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return Str::replace('\\', '\\\\', $value);
    }
}
