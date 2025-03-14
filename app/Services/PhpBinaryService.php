<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Support\ProcessUtils;

use function Illuminate\Support\php_binary;

class PhpBinaryService
{
    public function find(): string
    {
        if ($php = php_binary()) {
            return ProcessUtils::escapeArgument($php);
        }

        return 'php';
    }
}
