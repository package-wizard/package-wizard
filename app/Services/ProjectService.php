<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Str;

use function collect;
use function PackageWizard\Installer\resource_path;

class ProjectService
{
    public static function searchOn(): string
    {
        return __('info.packagist');
    }

    public function list(): array
    {
        return collect($this->rules())
            ->map(static fn (string $name) => Str::before($name, '.json'))
            ->push(static::searchOn())
            ->all();
    }

    protected function rules(): array
    {
        return File::names(resource_path('rules'), recursive: true);
    }
}
