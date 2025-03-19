<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Helpers;

use JsonException;
use PackageWizard\Installer\Data\ConfigData;

use function array_merge;
use function config;
use function file_exists;
use function file_get_contents;
use function is_file;
use function PackageWizard\Installer\resource_path;
use function realpath;

class ConfigHelper
{
    /**
     * @throws JsonException
     */
    public static function data(string $directory, string $package = 'default'): ConfigData
    {
        $data = static::getConfig($directory, $package);

        return ConfigData::from(array_merge($data, ['directory' => $directory]));
    }

    /**
     * @throws JsonException
     */
    protected static function getConfig(string $directory, string $package): array
    {
        if ($payload = static::search($directory, $package)) {
            return static::decode($payload);
        }

        return config('wizard.default');
    }

    protected static function search(string $directory, string $package): ?string
    {
        if ($path = static::path($directory, config('wizard.filename'))) {
            return $path;
        }

        return static::path(resource_path('rules'), $package . '.json');
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
}
