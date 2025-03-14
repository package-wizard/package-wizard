<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Exceptions;

use LogicException;

abstract class WizardLogicException extends LogicException
{
    protected array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
