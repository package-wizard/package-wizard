<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Str;

use function collect;
use function PackageWizard\Installer\resource_path;

class ProjectService
{
    public const Search = 'Search on Packagist';

    public function list(): array
    {
        return collect($this->search())
            ->map(static fn (string $name) => Str::before($name, '.json'))
            ->push(static::Search)
            ->all();
    }

    protected function search(): array
    {
        return File::names(resource_path('rules'), recursive: true);
    }
}
