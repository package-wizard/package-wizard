<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Str;

use function collect;

class ProjectService
{
    public const Search = 'Search on Packagist';

    protected string $basePath = __DIR__ . '/../../resources/rules';

    public function list(): array
    {
        return collect($this->search())
            ->map(static fn (string $name) => Str::before($name, '.json'))
            ->push(static::Search)
            ->all();
    }

    protected function search(): array
    {
        return File::names($this->basePath, recursive: true);
    }
}
