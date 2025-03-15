<?php

declare(strict_types=1);

namespace PackageWizard\Installer;

use function function_exists;
use function ltrim;

if (! function_exists('\PackageWizard\Installer\base_path')) {
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/../' . ltrim($path, '/\\');
    }
}

if (! function_exists('\PackageWizard\Installer\resource_path')) {
    function resource_path(string $path = ''): string
    {
        return base_path('resources/' . ltrim($path, '/\\'));
    }
}

if (! function_exists('\PackageWizard\Installer\vendor_path')) {
    function vendor_path(string $path = ''): string
    {
        return base_path('vendor/' . ltrim($path, '/\\'));
    }
}
