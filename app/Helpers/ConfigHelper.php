<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Helpers;

use PackageWizard\Installer\Data\ConfigData;

use function array_merge;
use function config;
use function file_exists;
use function file_get_contents;
use function is_file;
use function realpath;

class ConfigHelper
{
    public static string $directory = __DIR__ . '/../../resources/rules';

    protected static string $filename = 'wizard.json';

    /**
     * @throws \JsonException
     */
    public static function data(string $directory, string $package = 'default'): ConfigData
    {
        $data = ConfigData::from(
            static::getConfig($directory, $package)
        );

        $data->project->directory = $directory;

        return $data;
    }

    public static function search(string $directory, string $package): ?string
    {
        if ($path = static::path($directory, static::$filename)) {
            return $path;
        }

        return static::path(static::$directory, $package . '.json');
    }

    protected static function decode(string $payload): array
    {
        if (is_file($payload)) {
            $content = file_get_contents($payload);
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    protected static function path(string $directory, string $filename): ?string
    {
        if (file_exists($path = $directory . '/' . $filename)) {
            return realpath($path);
        }

        return null;
    }

    protected static function getConfig(string $directory, string $package): array
    {
        if ($payload = static::search($directory, $package)) {
            $payload = static::decode($payload);
        }

        return array_merge(config('wizard.default'), $payload ?? []);
    }
}
