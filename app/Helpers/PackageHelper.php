<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Helpers;

use function array_key_exists;
use function config;

class PackageHelper
{
    public static function isDev(string $name): bool
    {
        return array_key_exists($name, static::packages());
    }

    protected static function packages(): array
    {
        return config('boilerplate');
    }
}
