<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Helpers;

use Illuminate\Support\Str;
use PackageWizard\Installer\Data\ConfigData;

use function file_exists;
use function realpath;

class ConfigHelper
{
    public static string $directory = __DIR__ . '/../../resources/rules';

    protected static string $default = 'default.json';

    protected static string $filename = 'wizard.json';

    public static function data(string $directory, string $package = 'local'): ConfigData
    {
        $data = ConfigData::from(
            static::search($directory, $package)
        );

        $data->project->directory = $directory;

        return $data;
    }

    public static function search(string $directory, string $package = 'local'): string
    {
        if ($path = static::path($directory, static::$filename)) {
            return $path;
        }

        return static::path(static::$directory, Str::slug($package, '_'))
            ?? static::path(static::$directory, static::$default);
    }

    protected static function path(string $directory, string $filename): ?string
    {
        if (file_exists($path = $directory . '/' . $filename)) {
            return realpath($path);
        }

        return null;
    }
}
