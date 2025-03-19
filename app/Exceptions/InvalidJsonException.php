<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Exceptions;

class InvalidJsonException extends WizardLogicException
{
    public function __construct(string $path)
    {
        parent::__construct("Invalid content of JSON: \"$path\"");
    }
}
