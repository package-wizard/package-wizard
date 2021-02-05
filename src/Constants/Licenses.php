<?php

namespace Helldar\PackageWizard\Constants;

use Helldar\Support\Facades\Helpers\Filesystem\File;

final class Licenses
{
    public const DEFAULT_LICENSE = 'Other';

    public static function available(): array
    {
        return File::names(__DIR__ . '/../../resources/licenses');
    }

    public static function get(int $index): ?string
    {
        return self::available()[$index] ?? null;
    }
}
