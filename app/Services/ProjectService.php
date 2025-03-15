<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Str;

use function collect;
use function realpath;

class ProjectService
{
    public const Search = 'Search on Packagist';

    protected string $basePath = __DIR__ . '/../../resources/rules';

    protected array $exclude = [
        'default.json',
        'example.json',
    ];

    public function list(): array
    {
        return collect($this->search())
            ->map(static fn (string $name) => Str::before($name, '.json'))
            ->push(static::Search)
            ->all();
    }

    protected function search(): array
    {
        return File::names(
            path     : $this->basePath,
            callback : function (string $path): bool {
                $filename = Str::of(realpath($path))
                    ->after(realpath($this->basePath))
                    ->ltrim('/\\')
                    ->toString();

                return ! in_array($filename, $this->exclude, true);
            },
            recursive: true
        );
    }
}
