<?php

namespace Helldar\PackageWizard\Constants;

use Helldar\Support\Facades\Helpers\Filesystem\File;
use Helldar\Support\Facades\Helpers\Str;

final class Licenses
{
    public const DEFAULT_LICENSE = 'Other';

    public static function available(): array
    {
        return array_map(function ($name) {
            return Str::endsWith($name, '.stub') ? pathinfo($name, PATHINFO_FILENAME) : $name;
        }, static::all());
    }

    public static function get(int $index): ?string
    {
        return self::available()[$index] ?? null;
    }

    public static function all(): array
    {
        return File::names(__DIR__ . '/../../resources/licenses');
    }
}
