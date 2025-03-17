<?php

declare(strict_types=1);

use Illuminate\Support\Facades\ParallelTesting;

use function PackageWizard\Installer\base_path;

function temp_path(string $path = ''): string
{
    $token = ParallelTesting::token() ?: 0;

    return base_path("temp/$token/" . ltrim($path, '/'));
}
