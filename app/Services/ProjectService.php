<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Support\Str;

use function collect;
use function is_string;
use function PackageWizard\Installer\resource_path;

class ProjectService
{
    public static function searchOn(): string
    {
        return __('info.packagist');
    }

    public function list(): array
    {
        return collect($this->presets())
            ->merge($this->forced())
            ->reject(static fn (string $name) => $name === '.gitkeep')
            ->map(static fn (string $name) => Str::before($name, '.json'))
            ->map(static fn (string $name) => Str::replace('\\', '/', $name))
            ->unique()
            ->sort()
            ->push(static::searchOn())
            ->values()
            ->all();
    }

    protected function presets(): array
    {
        return File::names(resource_path('rules'), recursive: true);
    }

    protected function forced(): array
    {
        return collect(config('boilerplate'))
            ->map(static function (bool|string $value, int|string $key) {
                if (is_string($key) && is_bool($value)) {
                    return $key;
                }

                return $value;
            })
            ->all();
    }
}
