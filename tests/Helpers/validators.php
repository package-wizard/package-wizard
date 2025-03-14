<?php

declare(strict_types=1);

use PackageWizard\Installer\Services\SchemaValidatorService;

function validateSchema(string $filename): void
{
    $validator = new SchemaValidatorService();

    $validator->validate(rule($filename));
}
