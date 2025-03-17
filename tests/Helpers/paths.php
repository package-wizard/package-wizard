<?php

declare(strict_types=1);

use Illuminate\Support\Facades\ParallelTesting;

use function PackageWizard\Installer\base_path;

function temp_path(string $path = ''): string
{
    $token = (int) ParallelTesting::token();

    return base_path("temp/$token/" . ltrim($path, '/'));
}
