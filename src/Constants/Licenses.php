<?php

namespace Helldar\PackageWizard\Constants;

use Helldar\Support\Facades\Helpers\Filesystem\File;
use Helldar\Support\Facades\Helpers\Str;

final class Licenses
{
    public const DEFAULT_LICENSE = 'Other';

    public static function available(): array
    {
        $names = File::names(__DIR__ . '/../../resources/licenses');

        return array_map(fn ($name) => Str::endsWith($name, '.stub') ? pathinfo($name, PATHINFO_FILENAME) : $name, $names);
    }

    public static function get(int $index): ?string
    {
        return self::available()[$index] ?? null;
    }
}
